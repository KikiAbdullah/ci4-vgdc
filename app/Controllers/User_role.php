<?php

namespace App\Controllers;

use Irsyadulibad\DataTables\DataTables;

class User_role extends AdminController
{
    function __construct()
    {
        parent::__construct();
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
}
