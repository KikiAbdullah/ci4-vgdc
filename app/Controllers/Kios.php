<?php

namespace App\Controllers;

use Irsyadulibad\DataTables\DataTables;

class Kios extends AdminController
{
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['user_role'] = $this->m_user_role->findAll();
        $data['akses'] = $this->akses;

        return view('kios/index', $data);
    }


    function approve()
    {
        $data['gdc_temp'] = $this->m_gdc_temp->findAll();
        $data['akses'] = $this->akses;

        return view('kios/approve', $data);
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
