<?php

namespace App\Controllers;

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
        $data['menu_list'] =  $this->m_menu->where('jenis', 1)->orderBy('tipe', 'asc')->findAll();
        $data['akses']  = $this->akses;

        return view('user_role/index', $data);
    }

    function approve()
    {
        $data['user_role'] = $this->m_user_role->findAll();

        return view('user_role/aprove', @$data);
    }

    function get_data()
    {
        return DataTables::use('user_role')->select("id_user_role, user_role")
            ->where(['jenis' => 1])
            ->make(true);
    }

    function get_data_temp()
    {
        return DataTables::use('user_role_temp')->select("id_temp,user_role,user_role_temp.created_by as a,user_role_temp.created_at as b,tipe_aproval.ket as ket, user.nama as nama")
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
            // $this->form_validation->set_rules('user_role', 'User Role', 'trim|required|callback_is_exist[0,user_role]');

            // if ($this->form_validation->run() == FALSE) {
            if ($data) {


                $this->db->order_by('tipe');
                $data['menu_list'] = $this->db->get('menu')->result();
                $this->session->setFlashdata('postdata', $data);
                $this->session->setFlashdata('msg', warn_msg(validation_errors()));
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                $data['menu_akses'] = $this->get_akses($data);

                $wkt = date('Y-m-d H:i:s');
                $db_data = array(
                    'user_role' => $data['user_role'],
                    'menu_akses' => $data['menu_akses'],
                    'jenis' => '1',
                    'created_at' => $wkt,
                    'created_by' => $this->user->id_user,
                    'approval_tipe' => '1'
                );

                $proses = $this->global->saveData($this->table2, @$db_data);

                if ($proses) {

                    $log = array('id_user' => $this->user->id_user, 'aktivitas' => '5', 'tanggal' => date('Y-m-d'), 'waktu' => date('H:i:s'), 'keterangan' => 'Success create user role : ' . $data['user_role']);
                    $this->db->insert('log', $log);
                    $this->session->setFlashdata('msg', succ_msg('Data User Role Baru Berhasil Ditambahkan. Mohon Tunggu Proses Approval Administrator.'));
                } else {
                    $this->session->setFlashdata('msg', err_msg('Gagal menambahkan data.'));
                }

                redirect($this->cname);
            }
        } else {
            show_404();
        }
    }
}
