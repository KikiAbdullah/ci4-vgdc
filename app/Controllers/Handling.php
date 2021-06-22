<?php

namespace App\Controllers;

use Irsyadulibad\DataTables\DataTables;

class Handling extends AdminController
{
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // lakukan validasi
        $validation =  \Config\Services::validation();
        $isDataValid = $validation->withRequest($this->request)->run();

        $filter = $this->request->getPost();
        // print_r($filter); exit;
        $this->session->set('filter', $filter);
        $data['filter'] = $this->session->get('filter');

        $data['cs'] = $this->m_cs->findAll();
        $data['gdc'] = $this->m_gdc->findAll();
        $data['layanan'] = $this->m_layanan->findAll();

        $data['akses'] = $this->akses;

        return view('handling/index', $data);;
    }

    public function get_list_report()
    {
        $where1 = array();
        $where2 = array();

        $filter = $this->session->get('filter');
        if (!empty($filter['tanggal_awal']) && !empty($filter['tanggal_akhir'])) {

            $where1 = array(
                'date(tanggal) >=' => date('Y-m-d', strtotime($filter['tanggal_awal'])),
                'date(tanggal) <=' => date('Y-m-d', strtotime($filter['tanggal_akhir']))
            );
        }


        if (!empty($filter['id_layanan'])) {
            $where2 = array('transaksi.id_layanan' => $filter['id_layanan']);
        }

        return DataTables::use('transaksi')->select('transaksi.*,  transaksi.id_trx, layanan.nm_layanan as nm_layanan,  timediff(wkt_selesai, wkt_mulai) as waktu, gdc.lokasi as lokasi, gdc.nm_gdc, jenis_driver.nm_jenis as nm_jenis, tipe_driver.nm_tipe as nm_tipe, csat.gambar, nm_driver, tanggal, tipe_driver.nm_tipe, wkt_mulai, wkt_selesai, sessionid, sts_trx ')
            ->orderBy('tanggal', 'desc')
            ->orderBy('wkt_mulai', 'desc')
            ->join('layanan', 'transaksi.id_layanan = layanan.id_layanan', 'left')
            ->join('gdc', 'transaksi.id_gdc = gdc.id_gdc', 'left')
            ->join('jenis_driver', 'transaksi.id_jenis = jenis_driver.id_jenis', 'left')
            ->join('tipe_driver', 'transaksi.id_tipe = tipe_driver.id_tipe', 'left')
            ->join('csat', 'transaksi.id_csat = csat.id_csat', 'left')
            ->where(@$where1)
            ->where(@$where2)
            ->make(true);
    }

    public function reset_filter()
    {
        $this->session->remove('filter');
        return redirect()->to('handling');
    }
}
