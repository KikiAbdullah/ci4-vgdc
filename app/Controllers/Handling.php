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

        return DataTables::use('transaksi')->select('transaksi.*,  transaksi.id_trx, layanan.nm_layanan as nm_layanan,  timediff(wkt_selesai, wkt_mulai) as waktu, gdc.lokasi as lokasi, gdc.nm_gdc, jenis_driver.nm_jenis as nm_jenis, tipe_driver.nm_tipe as nm_tipe, csat.gambar, nm_driver, tanggal, tipe_driver.nm_tipe, wkt_mulai, wkt_selesai, sessionid, sts_trx,user.nama as nama_cs,')
            ->orderBy('tanggal', 'desc')
            ->orderBy('wkt_mulai', 'desc')
            ->join('layanan', 'transaksi.id_layanan = layanan.id_layanan', 'left')
            ->join('gdc', 'transaksi.id_gdc = gdc.id_gdc', 'left')
            ->join('jenis_driver', 'transaksi.id_jenis = jenis_driver.id_jenis', 'left')
            ->join('tipe_driver', 'transaksi.id_tipe = tipe_driver.id_tipe', 'left')
            ->join('csat', 'transaksi.id_csat = csat.id_csat', 'left')
            ->join('user','user.id_user = transaksi.id_cs')
            ->where(@$where1)
            ->where(@$where2)
            ->make(true);
    }

    public function reset_filter()
    {
        $this->session->remove('filter');
        return redirect()->to('handling');
    }

    public function updates_selesai($id_trx, $tampil)
    {
        // $id_trx = @$_REQUEST['id_trx'];

        $sts = 2;
        $data = @$this->m_transaksi
            ->where('id_trx', $id_trx)
            ->where('sts_trx', $sts)
            ->first();

        $idtx = $data['id_trx'];
        //echo json_encode(array('status' => 'sukses','data' => $data));

        if (!empty($data)) {
            $param_update = [
                'sts_trx' => 3,
                'wkt_selesai' => date('H:i:s'),
                'tampil' => $tampil,
                'id_cs' => $this->user['id_user']
            ];
            $this->m_transaksi->update($idtx, $param_update);

            // echo json_encode(array('status' => 'sukses', 'message' => 'OK', 'data_id_trx' => $idtx));
            return redirect()->to('handling');
        } else {
            echo json_encode(array('status' => '404', 'message' => 'Not Found'));
        }
    }
}
