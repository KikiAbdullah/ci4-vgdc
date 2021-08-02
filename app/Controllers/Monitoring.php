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
        if (empty($this->user)) {
            return redirect()->to('/login');
        }
        
        $data['title'] = $this->title;

        // lakukan validasi
        $validation =  \Config\Services::validation();
        $isDataValid = $validation->withRequest($this->request)->run();

        $filter = $this->request->getPost();
        // print_r($filter); exit;
        $this->session->set('filter', $filter);
        $data['filter'] = $this->session->get('filter');

        $data['log_gdc'] = $this->m_log_gdc->findAll();

        return view('monitoring/index', $data);
    }

    function get_data()
    {
        $filter = $this->session->get('filter');

        $dt_builder =  DataTables::use('log_gdc')->select('log_gdc.tanggal as tanggal,log_gdc.waktu as waktu,gdc.nm_gdc as nm_gdc, aktivitas_status.deskripsi as deskripsi, keterangan')
            ->join('gdc', 'gdc.id_gdc = log_gdc.id_gdcl', 'left')
            ->join('aktivitas_status', 'aktivitas_status.id_aktivitas = log_gdc.aktivitas', 'left')
            ->orderBy('tanggal', 'desc')
            ->orderBy('waktu', 'desc')
            ->orderBy('id_log', 'desc');

        if (!empty($filter['tanggal_awal']) && !empty($filter['tanggal_akhir'])) {
            $dt_builder = $dt_builder->where(
                array(
                    'date(tanggal) >=' => date('Y-m-d', strtotime($filter['tanggal_awal'])),
                    'date(tanggal) <=' => date('Y-m-d', strtotime($filter['tanggal_akhir']))
                )
            );
        }

        return $dt_builder->make(true);
    }


    public function reset_filter()
    {
        $this->session->remove('filter');
        return redirect()->to('monitoring');
    }
}
