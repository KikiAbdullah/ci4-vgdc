<?php

namespace App\Controllers;

use Irsyadulibad\DataTables\DataTables;

class Monitoring extends AdminController
{
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // lakukan validasi
        $filter = $this->request->getPost();
        $this->session->set('filter', $filter);
        $data['filter'] = $this->session->get('filter');

        $data['log_gdc'] = $this->m_log_gdc->findAll();

        return view('monitoring/index', $data);
    }

    function get_data()
    {
        $filter = $this->session->get('filter');

        $where = array();
        if (!empty($filter['tanggal_awal']) && !empty($filter['tanggal_akhir'])) {
            $where[] = array(
                'date(log_gdc.tanggal) >=' => date('Y-m-d', strtotime($filter['tanggal_awal'])),
                'date(log_gdc.tanggal) <=' => date('Y-m-d', strtotime($filter['tanggal_akhir']))
            );
        }

        return DataTables::use('log_gdc')->select('log_gdc.tanggal as tanggal,log_gdc.waktu as waktu,gdc.nm_gdc as nm_gdc, aktivitas_status.deskripsi as deskripsi, keterangan')
            ->join('gdc', 'gdc.id_gdc = log_gdc.id_gdcl', 'left')
            ->join('aktivitas_status', 'aktivitas_status.id_aktivitas = log_gdc.aktivitas', 'left')
            ->orderBy('tanggal', 'desc')
            ->orderBy('waktu', 'desc')
            ->where(@$where)
            ->make(true);
    }

    public function reset_filter()
    {
        $this->session->remove('filter');
        return redirect()->to('monitoring');
    }
}
