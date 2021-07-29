<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;
use Irsyadulibad\DataTables\DataTables;

class User_role extends AdminController
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

        $data['menu_list'] =  $this->m_menu->where('jenis', 1)->orderBy('tipe', 'asc')->findAll();
        $data['akses']  = $this->akses;

        return view('user_role/index', $data);
    }

    function edit($id = NULL)
    {
        if (empty($this->user)) {
            return redirect()->to('/login');
        }

        if (!$id || empty($this->akses[2])) throw PageNotFoundException::forPageNotFound();

        $data['menu_list'] = $this->m_menu->orderBy('tipe')->findAll();

        $data['data']   = @$this->session->getFlashdata('postdata') ? @$this->session->getFlashdata('postdata') : (array)$this->m_user_role->find(decode($id));

        if (!empty($data['data']['menu_akses'])) {
            $data['data']['menu_akses'] = @$this->get_akses($data['data']['menu_akses'], false);
        }

        return view('user_role/edit', $data);
    }

    function do_ubah()
    {
        $data = @$this->request->getPost();
        // $data = $this->security->xss_clean($data);

        if ($data) {
            $exist = $this->m_user_role->find($data['id_user_role']);
            // $this->form_validation->setRule('user_role', 'User Role', 'trim|required|callback_is_exist[' . $data['id_user_role'] . ',user_role]');

            if (empty($exist)) {

                $data['menu_list'] = $this->m_menu->orderBy('tipe')->findAll();
                $this->session->setFlashdata('postdata', $data);
                $this->session->setFlashdata('msg', warn_msg('User Role Sudah terdaftar'));
                return redirect()->to('user_role');
            } else {
                $wkt = date('Y-m-d H:i:s');
                $db_data = array(
                    'id_user_role' => $data['id_user_role'],
                    'user_role' => $data['user_role'],
                    'jenis' => '1',
                    'menu_akses' => $this->get_akses($data),
                    'created_at' => $wkt,
                    'created_by' => session()->get('user_login_vgdc')['id_user'],
                    'approval_tipe' => '2'
                );

                $proses2 = $this->m_user_role_temp->insert(@$db_data);
                if ($proses2) {

                    $log = array('id_user' => session()->get('user_login_vgdc')['id_user'], 'aktivitas' => '8', 'tanggal' => date('Y-m-d'), 'waktu' => date('H:i:s'), 'keterangan' => 'Success modify user role : ' . $data['user_role']);
                    $this->m_log->insert($log);

                    $this->session->setFlashdata('msg', succ_msg('Data berhasil diubah.Mohon Tunggu Proses Approval Administrator.'));
                } else {
                    $this->session->setFlashdata('msg', err_msg('Gagal merubah data.'));
                }
                return redirect()->to('user_role');
            }
        } else {
            throw PageNotFoundException::forPageNotFound();
        }
    }

    function approve()
    {
        if (empty($this->user)) {
            return redirect()->to('/login');
        }

        $data['user_role'] = $this->m_user_role->findAll();

        return view('user_role/approve', @$data);
    }

    function get_data()
    {
        return DataTables::use('user_role')->select("id_user_role, user_role")
            ->where(['jenis' => 1])
            ->make(true);
    }

    function get_data_temp()
    {
        return DataTables::use('user_role_temp')->select("id_temp,user_role,user_role_temp.created_by as a,user_role_temp.created_at as b, tipe_approval.ket as ket, user.nama as nama")
            ->where(['is_done' => 'N'])
            ->join('tipe_approval', 'tipe_approval.id_tipe = user_role_temp.approval_tipe', 'left')
            ->join('user', 'user.id_user = user_role_temp.created_by', 'left')
            ->make(true);
    }

    function do_tambah()
    {
        $data = @$this->request->getPost();
        // $data = $this->security->xss_clean($data);
        if ($data) {
            $exist = $this->m_user_role->where('user_role', $data['user_role'])->first();
            if (empty($exist)) {
                if ($this->form_validation->run($data, 'store_user_role') == FALSE) {

                    $data['menu_list'] = $this->m_menu->findAll();
                    $this->session->setFlashdata('postdata', $data);
                    $this->session->setFlashdata('msg', warn_msg('User Role Name harus diisi.'));
                    return redirect()->to('user_role');
                } else {
                    $data['menu_akses'] = $this->get_akses($data);

                    $wkt = date('Y-m-d H:i:s');
                    $db_data = array(
                        'user_role' => $data['user_role'],
                        'menu_akses' => $data['menu_akses'],
                        'jenis' => '1',
                        'created_at' => $wkt,
                        'created_by' => session()->get('user_login_vgdc')['id_user'],
                        'approval_tipe' => '1'
                    );

                    $proses = $this->m_user_role_temp->insert(@$db_data);

                    if ($proses) {
                        $log = array('id_user' => session()->get('user_login_vgdc')['id_user'], 'aktivitas' => '5', 'tanggal' => date('Y-m-d'), 'waktu' => date('H:i:s'), 'keterangan' => 'Success create user role : ' . $data['user_role']);
                        $this->m_log->insert($log);
                        $this->session->setFlashdata('msg', succ_msg('Data User Role Baru Berhasil Ditambahkan. Mohon Tunggu Proses Approval Administrator.'));
                    } else {
                        $this->session->setFlashdata('msg', err_msg('Gagal menambahkan data.'));
                    }

                    return redirect()->to('user_role');
                }
            } else {
                $this->session->setFlashdata('msg', warn_msg('User Role Name sudah tersedia.'));
                return redirect()->to('user_role');
            }
        } else {
            throw PageNotFoundException::forPageNotFound();
        }
    }

    function hapus($id = NULL)
    {
        if (!$id) throw PageNotFoundException::forPageNotFound();

        $data = $this->m_user_role->find(decode($id));

        if (empty($data)) {
            throw PageNotFoundException::forPageNotFound();
        }

        $nm = $data['user_role'];
        $wkt = date('Y-m-d H:i:s');
        $db_data = array(
            'id_user_role' => $data['id_user_role'],
            'user_role' => $data['user_role'],
            'jenis' => '1',
            'created_at' => $wkt,
            'created_by' => session()->get('user_login_vgdc')['id_user'],
            'approval_tipe' => '3'
        );
        $proses2 = $this->m_user_role_temp->insert(@$db_data);

        $log = array('id_user' => session()->get('user_login_vgdc')['id_user'], 'aktivitas' => '9', 'tanggal' => date('Y-m-d'), 'waktu' => date('H:i:s'), 'keterangan' => 'Success delete user role : ' . $nm);
        $this->m_log->insert($log);

        if ($proses2) {
            $this->session->setFlashdata('msg', succ_msg('Data User Role Akan Dihapus Setelah Disetujui oleh Admin.'));
        } else {
            $this->session->setFlashdata('msg', err_msg('Gagal menghapus.'));
        }

        return redirect()->to('user_role');
    }

    function get_akses($menu_akses, $join = true)
    {
        $hak_akses = [];
        if ($join) {

            $default_akses = array('baca', 'tambah', 'ubah', 'hapus', 'export', 'approvement');

            foreach ($menu_akses['id_menu'] as $value) {
                $akses = [];
                foreach ($default_akses as $a) {
                    if (empty($menu_akses['checkbox_' . $a][$value])) {
                        $akses[] = 0;
                    } else {
                        $akses[] = $menu_akses['checkbox_' . $a][$value];
                    }
                }
                $hak_akses[$value] = $value . ':' . implode('|', $akses);
            }

            $hak_akses = implode(',', $hak_akses);
        } else {
            $data = explode(',', $menu_akses);
            foreach ($data as $key => $value) {
                $isi = explode(':', $value);

                $index = $isi[0];
                $akses = explode('|', $isi[1]);

                $hak_akses[$index] = $akses;
            }
        }


        return $hak_akses;
    }

    public function approved($id_temp = '', $apr = '')
    {
        $data = $this->m_user_role_temp->find($id_temp);

        if ($apr == 'Y') {
            if ($data['approval_tipe'] == '1') {
                $datad['user_role'] = $data['user_role'];
                $datad['menu_akses'] = $data['menu_akses'];
                $datad['jenis'] = $data['jenis'];
                $datad['created_at'] = $data['created_at'];
                $datad['created_by'] = $data['created_by'];
                $wkt = date('Y-m-d H:i:s');
                $datad['approved_at'] = $wkt;
                $datad['approved_by'] = session()->get('user_login_vgdc')['id_user'];

                $proses = $this->m_user_role->insert(@$datad);

                if ($proses) {
                    $param_update = ['created_at' => $datad['created_at'], 'approved_at' => $datad['approved_at'], 'approved_by' => session()->get('user_login_vgdc')['id_user'], 'is_approved' => $apr, 'is_done' => 'Y'];
                    $this->m_user_role_temp->update($id_temp, $param_update);
                    $this->session->setFlashdata('msg', succ_msg('Penambahan Data User Role Telah Disetujui.'));
                    $log = array('id_user' => session()->get('user_login_vgdc')['id_user'], 'aktivitas' => '17', 'tanggal' => date('Y-m-d'), 'waktu' => date('H:i:s'), 'keterangan' => 'Success approve to create : ' . $data['user_role']);
                    $this->m_log->insert($log);
                } else {
                    $this->session->setFlashdata('msg', err_msg('Gagal Update Data.'));
                }
            }
            if ($data['approval_tipe'] == '2') {
                $datad['user_role'] = $data['user_role'];
                $datad['menu_akses'] = $data['menu_akses'];
                $datad['jenis'] = $data['jenis'];
                $datad['modified_at'] = $data['created_at'];
                $datad['modified_by'] = $data['created_by'];
                $wkt1 = date('Y-m-d H:i:s');
                $datad['approved_at'] = $wkt1;
                $datad['approved_by'] = session()->get('user_login_vgdc')['id_user'];

                $proses2 = $this->m_user_role->update($data['id_user_role'], @$datad);

                if ($proses2) {
                    $param_update =  ['created_at' => $data['created_at'], 'approved_at' => $datad['approved_at'], 'approved_by' => session()->get('user_login_vgdc')['id_user'], 'is_approved' => $apr, 'is_done' => 'Y'];

                    $this->m_user_role_temp->update($id_temp, @$param_update);

                    $this->session->setFlashdata('msg', succ_msg('Perubahan Data User Role Telah Disetujui.'));
                    $log = array('id_user' => session()->get('user_login_vgdc')['id_user'], 'aktivitas' => '17', 'tanggal' => date('Y-m-d'), 'waktu' => date('H:i:s'), 'keterangan' => 'Success approve to modify : ' . $data['user_role']);
                    $this->m_log->insert($log);
                } else {
                    $this->session->setFlashdata('msg', err_msg('Gagal Update Data.'));
                }
            }
            if ($data['approval_tipe'] == '3') {
                $wkt3 = date('Y-m-d H:i:s');

                $param_update = ['approved_at' => $wkt3, 'approved_by' => session()->get('user_login_vgdc')['id_user'], 'is_approved' => $apr, 'is_done' => 'Y'];

                $this->m_user_role_temp->update($id_temp, $param_update);

                $getid = $this->m_user_role_temp->find($id_temp)['id_user_role'];
                $hapus = $this->m_user_role->delete($getid);

                if ($hapus) {
                    $this->session->setFlashdata('msg', succ_msg('Penghapusan Data User Role Telah Disetujui.'));
                    $log = array('id_user' => session()->get('user_login_vgdc')['id_user'], 'aktivitas' => '17', 'tanggal' => date('Y-m-d'), 'waktu' => date('H:i:s'), 'keterangan' => 'Success approve delete user role');
                    $this->m_log->insert($log);
                }
            }
        } else {
            $wkt = date('Y-m-d H:i:s');

            $param_update = ['approved_at' => $wkt, 'approved_by' => session()->get('user_login_vgdc')['id_user'], 'is_approved' => $apr, 'is_done' => 'Y'];
            $this->m_user_role_temp->update($id_temp, $param_update);

            $this->session->setFlashdata('msg', succ_msg('Penambahan/Perubahan Data User Role Tidak Disetujui.'));
            $log = array('id_user' => session()->get('user_login_vgdc')['id_user'], 'aktivitas' => '18', 'tanggal' => date('Y-m-d'), 'waktu' => date('H:i:s'), 'keterangan' => 'Success reject create/modify : ' . $data['user_role']);
            $this->m_log->insert($log);
        }
        return redirect()->to('user_role');
    }
}
