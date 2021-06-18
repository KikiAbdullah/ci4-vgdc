<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Irsyadulibad\DataTables\DataTables;

class Log extends BaseController
{
    public function index()
    {
        // lakukan validasi
        $validation =  \Config\Services::validation();
        $isDataValid = $validation->withRequest($this->request)->run();

        $filter = $this->request->getPost();
        // print_r($filter); exit;
        $this->session->set('filter', $filter);
        $data['filter'] = $this->session->get('filter');

        $data['log'] = $this->m_log->findAll();

        return view('log/index', @$data);
    }

    function get_data()
    {
        $filter = $this->session->get('filter');


        $where = array();
        if (!empty($filter['tanggal_awal']) && !empty($filter['tanggal_akhir'])) {
            $where[] = array(
                'date(log.tanggal) >=' => date('Y-m-d', strtotime($filter['tanggal_awal'])),
                'date(log.tanggal) <=' => date('Y-m-d', strtotime($filter['tanggal_akhir']))
            );
        }

        return DataTables::use('log')->select('id_log, log.tanggal as tanggal, log.waktu as waktu, aktivitas, aktivitas_status.deskripsi as deskripsi, keterangan, user.nama as nama')
            ->join('user', 'user.id_user = log.id_user', 'left')
            ->join('aktivitas_status', 'aktivitas_status.id_aktivitas = log.aktivitas', 'left')
            ->orderBy('id_log', 'desc')
            ->where(@$where)
            ->make(true);
    }

    public function reset_filter()
    {
        $this->session->remove('filter');
        return redirect()->to('log');
    }
}
