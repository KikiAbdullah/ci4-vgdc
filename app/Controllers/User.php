<?php

namespace App\Controllers;

use Irsyadulibad\DataTables\DataTables;

class User extends AdminController
{
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['akses']  = $this->akses;
        $data['user_role'] = $this->m_user_role->findAll();
        return view('user/index', $data);
    }

    function approve()
    {
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
}
