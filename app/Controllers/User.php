<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;
use Irsyadulibad\DataTables\DataTables;

class User extends AdminController
{
    function __construct()
    {
        parent::__construct();
        $this->form_validation = \Config\Services::validation();
    }

    public function index()
    {
        if (empty($this->user)) {
            return redirect()->to('/login');
        }

        $data['title'] = $this->title;

        $data['akses']  = $this->akses;
        $data['user_role'] = $this->m_user_role->findAll();
        return view('user/index', $data);
    }

    function approve()
    {
        if (empty($this->user)) {
            return redirect()->to('/login');
        }

        $data['akses']  = $this->akses;
        $data['user_temp'] = $this->m_user_temp->findAll();
        return view('user/approve', @$data);
    }

    public function get_data()
    {
        return DataTables::use('user')->select("user.id_user, user.username, user.nama, user.email, user.status, user.id_user_role, user_role.user_role as user_role, ")
            ->join('user_role', 'user.id_user_role = user_role.id_user_role', 'left')
            ->make(true);
    }

    public function get_data_temp()
    {
        return DataTables::use('user_temp')->select("id_temp,user_temp.created_by as a,user_temp.created_at as b,username,nama,user_role.user_role as user_role, tipe_approval.ket as ket")
            ->join('tipe_approval', 'tipe_approval.id_tipe = user_temp.approval_tipe', 'left')
            ->join('user_role', 'user_role.id_user_role = user_temp.id_user_role', 'left')
            ->where(['is_done' => 'N'])
            ->make(true);
    }

    function do_tambah()
    {
        $data = @$this->request->getPost();
        // $data = $this->security->xss_clean($data);
        if ($data) {
            if ($this->form_validation->run($data, 'register') == FALSE) {
                // mengembalikan nilai input yang sudah dimasukan sebelumnya
                session()->setFlashdata('postdata', $this->request->getPost());
                // dd($this->form_validation->getErrors());
                $error = $this->form_validation->getErrors();
                foreach ($error as $error_msg) {
                    $this->session->setFlashdata('msg', warn_msg($error_msg));
                }
                // kembali ke halaman form
                // $this->session->setFlashdata('msg', warn_msg($this->form_validation->getErrors()));
                return redirect()->to('user');
            } else {
                $cek = $this->m_user->where('username', $data['username'])->first();
                if (!empty($cek)) {
                    $this->session->setFlashdata('postdata', (object)$this->request->getPost());
                    $this->session->setFlashdata('msg', warn_msg('Username tidak tersedia.'));
                    return redirect()->to('user');
                }

                if ($data['password'] != $data['confirm_password']) {
                    $this->session->setFlashdata('postdata', (object)$this->request->getPost());
                    $this->session->setFlashdata('msg', warn_msg('Bidang <b>Password</b> dan <b>Confirm Password</b> tidak sama'));
                    return redirect()->to('user');
                }

                $tgl1 = date('Y-m-d');
                $wkt = date('Y-m-d H:i:s');
                $password = md5($data['password']);

                $data['password'] = $password;
                $data['pwd_created'] = $tgl1;
                $data['pwd_exp'] = date('Y-m-d', strtotime('+3 month', strtotime($tgl1)));
                $data['created_at'] = $wkt;
                $data['created_by'] = session()->get('user_login_vgdc')['nama'];
                $data['is_approved'] = 'N';
                $data['approval_tipe'] = '1';
                unset($data['confirm_password']);
                unset($data['ci_csrf_token']);

                $proses = $this->m_user_temp->insert(@$data);
                $log = array(
                    'id_user' => session()->get('user_login_vgdc')['id_user'],
                    'aktivitas' => '5',
                    'tanggal' => date('Y-m-d'),
                    'waktu' => date('H:i:s'),
                    'keterangan' => 'Success create user : ' . $data['nama']
                );
                $this->m_log->insert($log);

                if ($proses) {
                    $this->session->setFlashdata('msg', succ_msg('Data User Baru Berhasil Ditambahkan. Mohon Tunggu Proses Approval Administrator.'));
                } else {
                    $this->session->setFlashdata('msg', err_msg('Gagal menambahkan data.'));
                }

                return redirect()->to('user');
            }
        } else {
            throw PageNotFoundException::forPageNotFound();
        }

        $this->templates->display('user/user');
    }

    function edit($id = NULL)
    {
        $data['user_role'] = $this->m_user_role->findAll();
        $data['item'] = $this->m_user->find($id);
        return view('user/edit', $data);
    }

    function do_ubah()
    {
        $data = @$this->request->getPost();

        // $data = $this->security->xss_clean($data);
        unset($data['ci_csrf_token']);

        if ($data) {
            if ($this->form_validation->run($data, 'update') == FALSE) {
                // mengembalikan nilai input yang sudah dimasukan sebelumnya
                session()->setFlashdata('postdata', $this->request->getPost());
                $error = $this->form_validation->getErrors();
                foreach ($error as $error_msg) {
                    $this->session->setFlashdata('msg', warn_msg($error_msg));
                }
                // kembali ke halaman form
                // $this->session->setFlashdata('msg', warn_msg($this->form_validation->getErrors()));
                return redirect()->to('user');
            } else {

                if ($data['password'] != '' && $data['confirm_password']) {

                    $datapass = $this->m_user_temp->select('password')
                        ->where('id_user', $data['id_user'])
                        ->orderBy('id_temp', 'desc')
                        ->findAll(5);

                    foreach ($datapass as $key => $v) {
                        if ($v['password'] == md5($data['password'])) {
                            $this->session->setFlashdata('msg', warn_msg('Password sudah pernah digunakan.'));
                            return redirect()->to('user');
                        }
                    }


                    if ($data['password'] != $data['confirm_password']) {
                        $this->session->setFlashdata('postdata', (object) @$this->request->getPost());
                        $this->session->setFlashdata('msg', warn_msg('Bidang <b>Password</b> dan <b>Confirm Password</b> tidak sama'));
                        return redirect()->to('user');
                    }
                    $password = md5($data['password']);
                    $data['password'] = $password;
                    unset($data['confirm_password']);
                    $tgl1 = date('Y-m-d');
                    $data['jml_login'] = 0;
                    $data['pwd_created'] = $tgl1;
                    $data['pwd_exp'] = date('Y-m-d', strtotime('+3 month', strtotime($tgl1)));
                } else {
                    $data_old = $this->m_user->find($data['id_user']);

                    unset($data['confirm_password']);
                    $data['password'] = $data_old['password'];
                    $data['jml_login'] = $data_old['jml_login'];
                    $data['pwd_created'] = $data_old['pwd_created'];
                    $data['pwd_exp'] = $data_old['pwd_exp'];
                }
                $wkt = date('Y-m-d H:i:s');
                $data['created_at'] = $wkt;
                $data['created_by'] = $this->session->get('user_login_vgdc')['nama'];
                $data['is_approved'] = 'N';
                $data['approval_tipe'] = '2';
                $data['is_done'] = 'N';
                $data['status'] = ($data['status'] == 'on') ? 'Y' : 'N';

                $proses2 = $this->m_user_temp->insert(@$data);
                if ($proses2) {

                    $log = array('id_user' => $this->session->get('user_login_vgdc')['id_user'], 'aktivitas' => '8', 'tanggal' => date('Y-m-d'), 'waktu' => date('H:i:s'), 'keterangan' => 'Success modify user : ' . $data['nama']);
                    $this->m_log->insert($log);

                    $this->session->setFlashdata('msg', succ_msg('Data berhasil diubah.Mohon Tunggu Proses Approval Administrator.'));
                } else {
                    $this->session->setFlashdata('msg', err_msg('Gagal mengubah data.'));
                }
                return redirect()->to('user');
            }
        } else {
            throw PageNotFoundException::forPageNotFound();
        }
    }


    function hapus($id = NULL)
    {
        if (!$id) throw PageNotFoundException::forPageNotFound();
        $data = $this->m_user->find(decode($id));
        if (empty($data)) {
            throw PageNotFoundException::forPageNotFound();
        }

        $nm = $data['nama'];
        $datad['id_user'] = $data['id_user'];
        $datad['username'] = $data['username'];
        $datad['password'] = $data['password'];
        $datad['nama'] = $data['nama'];
        $datad['id_user_role'] = $data['id_user_role'];
        $datad['email'] = $data['email'];
        $datad['status'] = $data['status'];
        $datad['pwd_created'] = $data['pwd_created'];
        $datad['pwd_exp'] = $data['pwd_exp'];
        $wkt = date('Y-m-d H:i:s');
        $datad['created_at'] = $wkt;
        $datad['created_by'] = session()->get('user_login_vgdc')['nama'];
        $datad['is_approved'] = 'N';
        $datad['approval_tipe'] = '3';
        $datad['is_done'] = 'N';

        $proses2 = $this->m_user_temp->insert(@$datad);
        $log = array('id_user' => session()->get('user_login_vgdc')['id_user'], 'aktivitas' => '9', 'tanggal' => date('Y-m-d'), 'waktu' => date('H:i:s'), 'keterangan' => 'Success delete user : ' . $nm);
        $this->m_log->insert($log);

        if ($proses2) {
            $this->session->setFlashdata('msg', succ_msg('Data User Akan Dihapus Setelah Disetujui oleh Admin.'));
        } else {
            $this->session->setFlashdata('msg', err_msg('Gagal menghapus.'));
        }

        return redirect()->to('user');
    }

    public function approved($id_temp = '', $apr = '')
    {
        $data = $this->m_user_temp->find($id_temp);

        //print_r($apr);exit;
        if ($apr == 'Y') {
            if ($data['approval_tipe'] == '1') {
                $tgl1 = date('Y-m-d');
                $wkt = date('Y-m-d H:i:s');

                $datad['username'] = $data['username'];
                $datad['password'] = $data['password'];
                $datad['nama'] = $data['nama'];
                $datad['id_user_role'] = $data['id_user_role'];
                $datad['email'] = $data['email'];
                $datad['status'] = 'Y';
                $datad['pwd_created'] = $tgl1;
                $datad['pwd_exp'] = date('Y-m-d', strtotime('+3 month', strtotime($tgl1)));
                $datad['created_at'] = $data['created_at'];
                $datad['created_by'] = $data['created_by'];
                $datad['approved_at'] = $wkt;
                $datad['approved_by'] = $this->session->get('user_login_vgdc')['id_user'];

                $proses = $this->m_user->insert(@$datad);

                if ($proses) {
                    $user_temp_update = [
                        'created_at' => $datad['created_at'],
                        'approved_at' => $datad['approved_at'],
                        'approved_by' => $this->session->get('user_login_vgdc')['id_user'],
                        'is_approved' => $apr,
                        'is_done' => 'Y'
                    ];

                    $is_update =  $this->m_user_temp->update($id_temp, $user_temp_update);

                    $this->session->setFlashdata('msg', succ_msg('Penambahan Data User Telah Disetujui.'));
                    $log = array(
                        'id_user' => $this->session->get('user_login_vgdc')['id_user'],
                        'aktivitas' => '17',
                        'tanggal' => date('Y-m-d'),
                        'waktu' => date('H:i:s'),
                        'keterangan' => 'Success approve to create : ' . $data['nama']
                    );
                    $this->m_log->insert($log);
                } else {
                    $this->session->setFlashdata('msg', err_msg('Gagal Update Data.'));
                }
            }
            if ($data['approval_tipe'] == '2') {
                $tgl1 = date('Y-m-d');
                $wkt = date('Y-m-d H:i:s');

                $datad['username'] = $data['username'];
                $datad['password'] = $data['password'];
                $datad['nama'] = $data['nama'];
                $datad['id_user_role'] = $data['id_user_role'];
                $datad['email'] = $data['email'];
                $datad['status'] = 'Y';
                $datad['pwd_created'] = $data['pwd_created'];
                $datad['pwd_exp'] = $data['pwd_exp'];
                $datad['modified_at'] = $data['created_at'];
                $datad['modified_by'] = $data['created_by'];
                $datad['approved_at'] = $wkt;
                $datad['approved_by'] = $this->session->get('user_login_vgdc')['id_user'];

                $proses2 = $this->m_user->update($data['id_user'], @$datad);

                if ($proses2) {
                    $update_user_temp = ['created_at' => $data['created_at'], 'approved_at' => $datad['approved_at'], 'approved_by' => $this->session->get('user_login_vgdc')['id_user'], 'is_approved' => $apr, 'is_done' => 'Y'];

                    $this->m_user_temp->update($id_temp, $update_user_temp);

                    $this->session->setFlashdata('msg', succ_msg('Perubahan Data User Telah Disetujui.'));
                    $log = array('id_user' => $this->session->get('user_login_vgdc')['id_user'], 'aktivitas' => '17', 'tanggal' => date('Y-m-d'), 'waktu' => date('H:i:s'), 'keterangan' => 'Success approve to modify : ' . $data['nama']);
                    $this->m_log->insert($log);
                } else {
                    $this->session->setFlashdata('msg', err_msg('Gagal Update Data.'));
                }
            }
            if ($data['approval_tipe'] == '3') {
                $wkt3 = date('Y-m-d H:i:s');

                $update_user_temp =  ['approved_at' => $wkt3, 'approved_by' => $this->session->get('user_login_vgdc')['id_user'], 'is_approved' => $apr, 'is_done' => 'Y'];
                $this->m_user_temp->update($id_temp, $update_user_temp);

                $get_id = $this->m_user_temp->find($id_temp)['id_user'];

                $hapus = $this->m_user->delete($get_id);
                if ($hapus) {
                    $this->session->setFlashdata('msg', succ_msg('Penghapusan Data User Telah Disetujui.'));
                    $log = array('id_user' => $this->session->get('user_login_vgdc')['id_user'], 'aktivitas' => '17', 'tanggal' => date('Y-m-d'), 'waktu' => date('H:i:s'), 'keterangan' => 'Success approve delete user');
                    $this->m_log->insert($log);
                }
            }
        } else {
            $wkt = date('Y-m-d H:i:s');

            $update_user_temp =  [
                'approved_at' => $wkt,
                'approved_by' => $this->session->get('user_login_vgdc')['id_user'],
                'is_approved' => $apr,
                'is_done' => 'Y'
            ];
            $this->m_user_temp->update($id_temp, $update_user_temp);

            $this->session->setFlashdata('msg', succ_msg('Penambahan/Perubahan Data User Tidak Disetujui.'));

            $log = array(
                'id_user' => $this->session->get('user_login_vgdc')['id_user'],
                'aktivitas' => '18',
                'tanggal' => date('Y-m-d'),
                'waktu' => date('H:i:s'),
                'keterangan' => 'Success reject create/modify : ' . $data['nama']
            );
            $this->m_log->insert($log);
        }
        return redirect()->to('user');
    }



    //PROFILE
    public function profile()
    {
        $data['user'] = $this->m_user->find($this->user['id_user']);
        $data['user_role'] = $this->m_user_role->findAll();

        return view('profile/index', $data);
    }

    public function do_ubah_profile()
    {
        $data = @$this->request->getPost();
        $tgl1 = date('Y-m-d');

        if ($data['password'] != '' && $data['confirm_password']) {

            $datapass = $this->m_user_temp->select('password')
                ->where('id_user', $data['id_user'])
                ->orderBy('id_temp', 'desc')
                ->findAll(5);

            foreach ($datapass as $key => $v) {
                if ($v['password'] == md5($data['password'])) {
                    $this->session->setFlashdata('msg', warn_msg('Password sudah pernah digunakan.'));
                    return redirect()->to('user/profile');
                }
            }


            if ($data['password'] != $data['confirm_password']) {
                $this->session->setFlashdata('postdata', (object)@$this->request->getPost());
                $this->session->setFlashdata('msg', warn_msg('Bidang <b>Password</b> dan <b>Confirm Password</b> tidak sama'));
                return redirect()->to('user/profile');
            }
            $password = md5($data['password']);
            $data['password'] = $password;
            unset($data['confirm_password']);
            $data['pwd_created'] = $tgl1;
            $data['pwd_exp'] = date('Y-m-d', strtotime('+3 month', strtotime($tgl1)));
        } else {
            $passwordlama = $this->m_user->find($data['id_user'])['password'];

            unset($data['confirm_password']);
            $data['password'] = $passwordlama;
        }
        unset($data['ci_csrf_token']);
        $datad['nama'] = $data['nama'];
        $datad['email'] = $data['email'];
        $data['modified_at'] = $tgl1;
        $data['modified_by'] = $this->session->get('user_login_vgdc')['nama'];

        $proses2 = $this->m_user->update($data['id_user'], @$data);

        if ($proses2) {

            $log = array('id_user' => $this->session->get('user_login_vgdc')['id_user'], 'aktivitas' => '8', 'tanggal' => date('Y-m-d'), 'waktu' => date('H:i:s'), 'keterangan' => 'Success modify user : ' . $data['nama']);
            $this->m_log->insert($log);

            $this->session->setFlashdata('msg', succ_msg('Data berhasil diubah.'));
        } else {
            $this->session->setFlashdata('msg', err_msg('Gagal mengubah data.'));
        }
        return redirect()->to('user/profile');
    }
}
