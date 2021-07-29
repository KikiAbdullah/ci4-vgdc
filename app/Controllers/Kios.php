<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;
use Irsyadulibad\DataTables\DataTables;

class Kios extends AdminController
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

        $data['user_role'] = $this->m_user_role->findAll();
        $data['akses'] = $this->akses;

        return view('kios/index', $data);
    }


    function approve()
    {
        if (empty($this->user)) {
            return redirect()->to('/login');
        }

        $data['gdc_temp'] = $this->m_gdc_temp->findAll();
        $data['akses'] = $this->akses;

        return view('kios/approve', $data);
    }

    function do_tambah()
    {
        $data = @$this->request->getPost();
        // $data = $this->security->xss_clean($data);
        if ($data) {
            if ($this->form_validation->run($data, 'store_kios') == FALSE) {
                session()->setFlashdata('postdata', $this->request->getPost());
                $error = $this->form_validation->getErrors();
                foreach ($error as $error_msg) {
                    $this->session->setFlashdata('msg', warn_msg($error_msg));
                }
                return redirect()->to('kios');
            } else {
                $cek = $this->m_gdc->where('nm_gdc', $data['nm_gdc'])->first();
                if (!empty($cek)) {
                    session()->setFlashdata('postdata', $this->request->getPost());
                    $this->session->setFlashdata('msg', warn_msg('Username tidak tersedia.'));
                    return redirect()->to('kios');
                }

                if ($data['password'] != $data['confirm_password']) {
                    session()->setFlashdata('postdata', $this->request->getPost());
                    $this->session->setFlashdata('msg', warn_msg('Bidang <b>Password</b> dan <b>Confirm Password</b> tidak sama'));
                    return redirect()->to('kios');
                }

                $password = md5($data['password']);
                $data['password'] = $password;
                unset($data['confirm_password']);
                unset($data['ci_csrf_token']);

                $wkt = date('Y-m-d H:i:s');
                $data['created_at'] = $wkt;
                $data['created_by'] = session()->get('user_login_vgdc')['id_user'];
                $data['is_approved'] = 'N';
                $data['approval_tipe'] = '1';

                $proses = $this->m_gdc_temp->insert(@$data);

                $log = array('id_user' => session()->get('user_login_vgdc')['id_user'], 'aktivitas' => '5', 'tanggal' => date('Y-m-d'), 'waktu' => date('H:i:s'), 'keterangan' => 'Success create kiosk : ' . $data['nm_gdc']);
                $this->m_log->insert($log);

                if ($proses) {
                    $this->session->setFlashdata('msg', succ_msg(' Data GDC Kiosk Baru Berhasil Ditambahkan. Mohon Tunggu Proses Approval Administrator.'));
                } else {
                    $this->session->setFlashdata('msg', err_msg(' Data GDC Kiosk Gagal Ditambahkan.'));
                }

                return redirect()->to('kios');
            }
        } else {
            throw PageNotFoundException::forPageNotFound();
        }
    }

    function edit($id = NULL)
    {

        if (empty($this->user)) {
            return redirect()->to('/login');
        }

        if (!$id) throw PageNotFoundException::forPageNotFound();
        $data['item'] = @$this->session->getFlashdata('postdata') ? @$this->session->getFlashdata('postdata') : $this->m_gdc->find(decode($id));

        return view('kios/edit', $data);
    }

    function do_ubah()
    {
        $data = @$this->request->getPost();
        // $data = $this->security->xss_clean($data);
        // print_r($data);exit;
        if ($data) {
            if ($this->form_validation->run($data, 'update_kios') == FALSE) {
                $this->session->setFlashdata('postdata', (object)$this->request->getPost());
                // dd($this->form_validation->getErrors());

                $error = $this->form_validation->getErrors();
                foreach ($error as $error_msg) {
                    $this->session->setFlashdata('msg', warn_msg($error_msg));
                }
                return redirect()->to('kios');
            } else {
                if ($data['password'] != '' && $data['confirm_password']) {
                    if ($data['password'] != $data['confirm_password']) {
                        $this->session->setFlashdata('postdata', (object)$this->request->getPost());
                        $this->session->setFlashdata('msg', warn_msg('Bidang <b>Password</b> dan <b>Confirm Password</b> tidak sama'));
                        return redirect()->to('kios');
                    }

                    $password = md5($data['password']);
                    $data['password'] = $password;
                    unset($data['confirm_password']);
                } else {
                    $passwordlama = $this->m_gdc->find($data['id_gdc'])['password'];

                    unset($data['confirm_password']);
                    $data['password'] = $passwordlama;
                }
                unset($data['ci_csrf_token']);

                if (!empty($data['status'])) {
                    if ($data['status'] == 'on') {
                        $status = 'Y';
                    } else {
                        $status = 'N';
                    }
                } else {
                    $status = 'N';
                }

                $data['status'] = $status;
                $wkt = date('Y-m-d H:i:s');
                $data['created_at'] = $wkt;
                $data['created_by'] = session()->get('user_login_vgdc')['id_user'];
                $data['is_approved'] = 'N';
                $data['approval_tipe'] = '2';
                $data['is_done'] = 'N';

                $proses2 = $this->m_gdc_temp->insert(@$data);

                if ($proses2) {
                    $log = array('id_user' => session()->get('user_login_vgdc')['id_user'], 'aktivitas' => '8', 'tanggal' => date('Y-m-d'), 'waktu' => date('H:i:s'), 'keterangan' => 'Success modify kiosk : ' . $data['nm_gdc']);
                    $this->m_log->insert($log);

                    $this->session->setFlashdata('msg', succ_msg('Data berhasil diubah.Mohon Tunggu Proses Approval Administrator'));
                } else {
                    $this->session->setFlashdata('msg', err_msg('Gagal mengubah data.'));
                }
                return redirect()->to('kios');
            }
        } else {
            throw PageNotFoundException::forPageNotFound();
        }
    }

    function hapus($id = NULL)
    {
        if (!$id) throw PageNotFoundException::forPageNotFound();
        $data = $this->m_gdc->find(decode($id));
        if (empty($data)) {
            throw PageNotFoundException::forPageNotFound();
        }

        $nm = $data['nm_gdc'];
        $datad['id_gdc'] = $data['id_gdc'];
        $datad['nm_gdc'] = $data['nm_gdc'];
        $datad['lokasi'] = $data['lokasi'];
        $datad['email_gdc'] = $data['email_gdc'];
        $datad['password'] = $data['password'];
        $datad['id_device'] = $data['id_device'];
        $datad['apikey'] = $data['apikey'];
        $datad['ip_gdc'] = $data['ip_gdc'];
        $datad['ipserver'] = $data['ipserver'];
        $datad['pwr'] = $data['pwr'];
        $datad['status'] = $data['status'];
        $wkt = date('Y-m-d H:i:s');
        $datad['created_at'] = $wkt;
        $datad['created_by'] = session()->get('user_login_vgdc')['id_user'];
        $datad['is_approved'] = 'N';
        $datad['approval_tipe'] = '3';
        $datad['is_done'] = 'N';

        $proses2 = $this->m_gdc_temp->insert(@$datad);

        $log = array('id_user' => session()->get('user_login_vgdc')['id_user'], 'aktivitas' => '9', 'tanggal' => date('Y-m-d'), 'waktu' => date('H:i:s'), 'keterangan' => 'Success delete kiosk : ' . $nm);
        $this->m_log->insert($log);
        if ($proses2) {
            $this->session->setFlashdata('msg', succ_msg('Data GDC Kiosk Akan Dihapus Setelah Disetujui oleh Admin.'));
        } else {
            $this->session->setFlashdata('msg', err_msg('Gagal menghapus.'));
        }
        return redirect()->to('kios');
    }


    public function approved($id_temp = '', $apr = '')
    {
        if (empty($this->user)) {
            return redirect()->to('/login');
        }

        $data = $this->m_gdc_temp->find($id_temp);

        //print_r($apr);exit;
        if ($apr == 'Y') {
            if ($data['approval_tipe'] == '1') {
                $datad['nm_gdc'] = $data['nm_gdc'];
                $datad['lokasi'] = $data['lokasi'];
                $datad['email_gdc'] = $data['email_gdc'];
                $datad['password'] = $data['password'];
                $datad['id_device'] = $data['id_device'];
                $datad['apikey'] = $data['apikey'];
                $datad['ip_gdc'] = $data['ip_gdc'];
                $datad['ipserver'] = $data['ipserver'];
                $datad['pwr'] = $data['pwr'];
                $datad['status'] = 'Y';
                $datad['created_at'] = $data['created_at'];
                $datad['created_by'] = $data['created_by'];
                $wkt = date('Y-m-d H:i:s');
                $datad['approved_at'] = $wkt;
                $datad['approved_by'] = session()->get('user_login_vgdc')['id_user'];

                $proses = $this->m_gdc->insert(@$datad);

                if ($proses) {
                    $param_update = ['created_at' => $datad['created_at'], 'approved_at' => $datad['approved_at'], 'approved_by' => session()->get('user_login_vgdc')['id_user'], 'is_approved' => $apr, 'is_done' => 'Y'];
                    $this->m_gdc_temp->update($id_temp, $param_update);
                    $this->session->setFlashdata('msg', succ_msg('Penambahan Data GDC Kiosk Telah Disetujui.'));
                    $log = array('id_user' => session()->get('user_login_vgdc')['id_user'], 'aktivitas' => '17', 'tanggal' => date('Y-m-d'), 'waktu' => date('H:i:s'), 'keterangan' => 'Success approve to create : ' . $data['nm_gdc']);
                    $this->m_log->insert($log);
                } else {
                    $this->session->setFlashdata('msg', err_msg('Gagal Update Data.'));
                }
            }
            if ($data['approval_tipe'] == '2') {
                $datad['nm_gdc'] = $data['nm_gdc'];
                $datad['lokasi'] = $data['lokasi'];
                $datad['email_gdc'] = $data['email_gdc'];
                $datad['password'] = $data['password'];
                $datad['id_device'] = $data['id_device'];
                $datad['apikey'] = $data['apikey'];
                $datad['ip_gdc'] = $data['ip_gdc'];
                $datad['ipserver'] = $data['ipserver'];
                $datad['pwr'] = $data['pwr'];
                $datad['status'] = $data['status'];
                $datad['modified_at'] = $data['created_at'];
                $datad['modified_by'] = $data['created_by'];
                $wkt1 = date('Y-m-d H:i:s');
                $datad['approved_at'] = $wkt1;
                $datad['approved_by'] = session()->get('user_login_vgdc')['id_user'];
                //$datad['created_at'] = $data['created_at;

                $proses2 = $this->m_gdc->update($data['id_gdc'], @$datad);

                if ($proses2) {
                    $param_update = ['created_at' => $data['created_at'], 'approved_at' => $datad['approved_at'], 'approved_by' => session()->get('user_login_vgdc')['id_user'], 'is_approved' => $apr, 'is_done' => 'Y'];
                    $this->m_gdc_temp->update($id_temp, $param_update);

                    $this->session->setFlashdata('msg', succ_msg('Perubahan Data GDC Kiosk Telah Disetujui.'));
                    $log = array('id_user' => session()->get('user_login_vgdc')['id_user'], 'aktivitas' => '17', 'tanggal' => date('Y-m-d'), 'waktu' => date('H:i:s'), 'keterangan' => 'Success approve to modify : ' . $data['nm_gdc']);
                    $this->m_log->insert($log);
                } else {
                    $this->session->setFlashdata('msg', err_msg('Gagal Update Data.'));
                }
            }
            if ($data['approval_tipe'] == '3') {
                $wkt3 = date('Y-m-d H:i:s');
                $param_update = ['approved_at' => $wkt3, 'approved_by' => session()->get('user_login_vgdc')['id_user'], 'is_approved' => $apr, 'is_done' => 'Y'];
                $this->m_gdc_temp->update($id_temp, $param_update);

                $getid = $this->m_gdc_temp->find($id_temp)['id_gdc'];
                $hapus = $this->m_gdc->delete($getid);
                if ($hapus) {
                    $this->session->setFlashdata('msg', succ_msg('Penghapusan Data GDC Kiosk Telah Disetujui.'));
                    $log = array('id_user' => session()->get('user_login_vgdc')['id_user'], 'aktivitas' => '17', 'tanggal' => date('Y-m-d'), 'waktu' => date('H:i:s'), 'keterangan' => 'Success approve delete kiosk');
                    $this->m_log->insert($log);
                }
            }
        } else {
            $wkt = date('Y-m-d H:i:s');

            $param_update = ['approved_at' => $wkt, 'approved_by' => session()->get('user_login_vgdc')['id_user'], 'is_approved' => $apr, 'is_done' => 'Y'];
            $this->m_gdc_temp->update($id_temp, $param_update);

            $this->session->setFlashdata('msg', succ_msg('Penambahan/Perubahan Data GDC Kiosk Tidak Disetujui.'));
            $log = array('id_user' => session()->get('user_login_vgdc')['id_user'], 'aktivitas' => '18', 'tanggal' => date('Y-m-d'), 'waktu' => date('H:i:s'), 'keterangan' => 'Success reject create/modify : ' . $data['nm_gdc']);
            $this->m_log->insert($log);
        }
        return redirect()->to('kios');
    }

    function get_data()
    {
        return DataTables::use('gdc')->select("id_gdc, nm_gdc, email_gdc, lokasi, status, ip_gdc, ipserver, apikey, id_device")
            ->make(true);
    }

    function get_data_temp()
    {
        return DataTables::use('gdc_temp')->select("id_temp,nm_gdc,lokasi, apikey, id_device,gdc_temp.created_by as a,gdc_temp.created_at as b,tipe_approval.ket as ket, user.nama as nama")
            ->join('tipe_approval', 'tipe_approval.id_tipe = gdc_temp.approval_tipe', 'left')
            ->join('user', 'user.id_user = gdc_temp.created_by', 'left')
            ->where(['is_done' => 'N'])
            ->make(true);
    }
}
