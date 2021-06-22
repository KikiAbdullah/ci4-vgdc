<?php

namespace App\Controllers;

use App\Models\Log;
use App\Models\Menu;
use App\Models\User;
use CodeIgniter\HTTP\RequestInterface;
use DateTime;

class Login extends BaseController
{
    public function __construct()
    {
        $this->m_log = new Log();
        $this->m_user = new User();
        $this->m_menu = new Menu();
    }
    public function index()
    {
        if (!empty($this->session->get('user_login_vgdc'))) {
            return redirect()->to('dashboard');
        } else {
            return view('auth/login');
        }
    }

    public function proses()
    {
        // lakukan validasi
        $validation =  \Config\Services::validation();
        $isDataValid = $validation->withRequest($this->request)->run();


        $session  = $this->session->get('user_login_vgdc');

        if ($session != '') {
            return redirect()->to('dashboard/report');
        }
        $data = $this->request->getPost();

        $tgl = date('Y-m-d');
        $cek = $this->m_user
            ->join('user_role', 'user.id_user_role = user_role.id_user_role', 'left')
            ->where('user.username', $data['username'])
            ->where('user.password', md5($data['password']))
            ->select('user.*, user_role.menu_akses')
            ->first();

        if (!empty($cek)) {

            if ($cek['pwd_exp'] > $tgl) {

                $log = array(
                    'id_user' => $cek['id_user'],
                    'aktivitas' => '1',
                    'tanggal' => date('Y-m-d'),
                    'waktu' => date('H:i:s'),
                    'keterangan' => 'User login success'
                );
                $this->m_log->insert($log);

                $update = array('jml_login' => '0');
                $this->m_user->update($cek['id_user'], $update);

                $tgly = new DateTime($cek['pwd_exp']);
                $tgls = new DateTime();
                $selisih = $tgls->diff($tgly)->days;

                $this->session->setFlashdata('msg', warn_msg(' Anda Berhasil Login. Masa berlaku password Anda tinggal ' . $selisih . ' hari.'));
                $this->generate_menu($cek);
                $this->session->set('user_login_vgdc', $cek);

                return redirect()->to('dashboard');
            } else {
                $this->session->setFlashdata('msg', 'Password Anda Expired, Silahkan hubungi administrator.');
                return redirect()->to('login');
            }
        } else {
            $cek2 = $this->m_user
                ->where('user.username', $data['username'])
                ->orWhere('user.password', md5($data['password']))
                ->first();

            $jum = $cek2['jml_login'] + 1;
            $this->m_user->update($cek2['id_user'], array('jml_login' => $jum));

            if ($cek2['jml_login'] == 0) {
                $log = array(
                    'id_user' => $cek2['id_user'],
                    'aktivitas' => '1',
                    'tanggal' => date('Y-m-d'),
                    'waktu' => date('H:i:s'),
                    'keterangan' => 'User login failed'
                );
                $this->m_log->insert($log);

                $this->session->setFlashdata('msg', 'Data yang anda masukkan salah. Jumlah login 1x');
            }

            if ($cek2['jml_login'] == 1) {
                $log = array(
                    'id_user' => $cek2['id_user'],
                    'aktivitas' => '1',
                    'tanggal' => date('Y-m-d'),
                    'waktu' => date('H:i:s'),
                    'keterangan' => 'User login failed'
                );
                $this->m_log->insert($log);

                $this->session->setFlashdata('msg', 'Data yang anda masukkan salah. Jumlah login 2x');
            }

            if ($cek2['jml_login'] == 2) {
                $this->m_user->update($cek2['id_user'], ['status' => 'N']);

                $log = array(
                    'id_user' => $cek2['id_user'],
                    'aktivitas' => '4',
                    'tanggal' => date('Y-m-d'),
                    'waktu' => date('H:i:s'),
                    'keterangan' => 'User login failed - 3 times'
                );
                $this->m_log->insert($log);

                $this->session->setFlashdata('msg', 'Anda sudah gagal login 3x. User anda sudah diblokir, Silahkan hubungi administrator.');
            }
            if ($cek2['jml_login'] > 2) {
                $this->session->setFlashdata('msg', 'User anda telah diblokir, Silahkan hubungi administrator.');
            }
            return redirect()->to('login');
        }
    }

    public function logout()
    {
        // session_destroy();
        $id_user = @$this->session->get('user_login_vgdc')['id_user'];
        if (!empty($id_user)) {

            $log = array(
                'id_user' => $id_user,
                'aktivitas' => '2',
                'tanggal' => date('Y-m-d'),
                'waktu' => date('H:i:s'),
                'keterangan' => 'User logout success'
            );
            $this->m_log->insert($log);

            $this->session->destroy();
            return redirect()->to('login');
        } else {
            return redirect()->to('login');
        }
    }

    public function logout_idle()
    {
        // session_destroy();
        $id_user = @$this->session->get('user_login_vgdc')['id_user'];

        if (!empty($id_user)) {

            $log = array('id_user' => $id_user, 'aktivitas' => '3', 'tanggal' => date('Y-m-d'), 'waktu' => date('H:i:s'), 'keterangan' => 'Idle user logout');
            $this->m_log->insert($log);

            $this->session->destroy();
            return redirect()->to('login');
        } else {
            return redirect()->to('login');
        }
    }


    function generate_menu(&$user_login_online)
    {
        $data_menu = $this->m_menu->findAll();
        $menu_akses = explode(',', $user_login_online['menu_akses']);


        foreach ($menu_akses as $key => $value) {
            $isi = explode(':', $value);

            $index = $isi[0];
            $akses = explode('|', $isi[1]);

            $hak_akses[$index] = $akses;
        }


        $data = array();
        foreach ($data_menu as $key => $c) {
            if ($hak_akses[$c['id_menu']][0] == 1) {
                $c['akses'] = $hak_akses[$c['id_menu']];
                $data[$c['tipe']][] = $c;
            }
        }

        $user_login_online['menu'] = $data;

        $user_login_online['hak_akses'] = $hak_akses;
    }
}
