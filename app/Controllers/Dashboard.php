<?php

namespace App\Controllers;

use App\Models\Cs;
use App\Models\Gdc;
use App\Models\Layanan;
use App\Models\Transaksi;

class Dashboard extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->m_cs = new Cs();
        $this->m_gdc = new Gdc();
        $this->m_layanan = new Layanan();
        $this->m_transaksi = new Transaksi();

        $this->cname = 'dashboard';
    }

    public function index()
    {
        $data['title'] = $this->title;

        return view('dashboard/index', $data);
    }

    public function video_call()
    {
        // lakukan validasi
        $validation =  \Config\Services::validation();

        $isDataValid = $validation->withRequest($this->request)->run();

        $id_cs = $this->user->id_cs;
        $data['id_trx'] = @$this->request->getPost('id_trx');

        $data['item'] = $this->m_transaksi->select('transaksi.*, layanan.nm_layanan,  timediff(wkt_selesai, wkt_mulai) as waktu, gdc.lokasi')
            ->join('layanan', 'transaksi.id_layanan = layanan.id_layanan', 'left')
            ->join('gdc', 'transaksi.id_gdc = gdc.id_gdc', 'left')
            ->where('id_trx', $data['id_trx'])
            ->findAll();

        if (!empty($data['item'])) {
            $layanan = $this->m_layanan->find($data['item']['id_layanan']);

            $result = $this->db->update('transaksi', array('sts_trx' => 2, 'id_cs' => $id_cs), array('id_trx' => $data['id_trx']));

            $update = array('sts_trx' => 2, 'id_cs' => $id_cs);
            $result = $this->m_transaksi->update($data['id_trx'], $update);
        }

        if ($result) {

            //file_get_contents($this->server_url . '/service/update_sts');
            $data['link'] = @$this->request->getPost('link');
            return view('dashboard/video_call', $data);
        } else {
            $this->session->setFlashdata('msg', err_msg($result->msg));
            redirect('dashboard');
        }
    }

    public function video_call_reconnect()
    {
        // lakukan validasi
        $validation =  \Config\Services::validation();
        $isDataValid = $validation->withRequest($this->request)->run();

        $id_cs = $this->user->id_cs;
        $data['id_trx'] = @$this->request->getPost('id_trx');

        $data['item'] = $this->m_transaksi->select('transaksi.*, layanan.nm_layanan,  timediff(wkt_selesai, wkt_mulai) as waktu, gdc.lokasi')
            ->join('layanan', 'transaksi.id_layanan = layanan.id_layanan', 'left')
            ->join('gdc', 'transaksi.id_gdc = gdc.id_gdc', 'left')
            ->where('id_trx', $data['id_trx'])->first();

        if (!empty($data['item'])) {
            $layanan = $this->m_layanan->find($data['item']['id_layanan']);

            $update = array('sts_trx' => 2, 'id_cs' => $id_cs);
            $result = $this->m_transaksi->update($data['id_trx'], $update);
        }

        if ($result) {
            $data['link'] = @$this->request->getPost('link');
            return view('dashboard/video_call', $data);
        } else {
            $this->session->setFlashdata('msg', err_msg($result->msg));
            redirect('dashboard');
        }
    }


    public function report()
    {
        $validation =  \Config\Services::validation();

        $isDataValid = $validation->withRequest($this->request)->run();

        $filter = $this->request->getPost();

        $this->session->set('filter', $filter);
        $data['filter'] = $this->session->get('filter');

        $data['cs'] = $this->m_cs->findAll();
        $data['gdc'] = $this->m_gdc->findAll();
        $data['layanan'] = $this->m_layanan->findAll();


        return view('dashboard/report', $data);
    }


    public function report_csat()
    {
        $validation =  \Config\Services::validation();
        $isDataValid = $validation->withRequest($this->request)->run();

        $filter = $this->request->getPost();
        // print_r($filter); exit;
        $this->session->set('filter', $filter);
        $data['filter'] = $this->session->get('filter');

        $data['cs'] = $this->m_cs->findAll();
        $data['gdc'] = $this->m_gdc->findAll();
        $data['layanan'] = $this->m_layanan->findAll();

        return view('dashboard/report_csat', $data);
    }

    public function reset_filter()
    {
        $this->session->unset('filter');
        redirect($this->cname . '/report');
    }

    public function reset_filter_csat()
    {
        $this->session->unset('filter');
        redirect($this->cname . '/report_csat');
    }

    public function detail_report($id = '')
    {
        $id = $_REQUEST['id_trx'];
        $data['item'] = $this->m_transaksi->select('transaksi.*, layanan.nm_layanan,  timediff(wkt_selesai, wkt_mulai) as waktu, gdc.lokasi')
            ->join('layanan', 'transaksi.id_layanan = layanan.id_layanan', 'left')
            ->join('gdc', 'transaksi.id_gdc = gdc.id_gdc', 'left')
            ->where('id_trx', $id)
            ->first();

        if (!empty($data['item'])) {
            $data['detail'] = $this->m_file->select('file.*, dokumen.nm_dok')
                ->join('dokumen', 'file.id_dok = dokumen.id_dok', 'left')
                ->where('id_trx', $data['item']['id_trx'])
                ->findAll();
        }

        return view('dashboard/detail_report', @$data);
    }

    public function get_list_dokumen()
    {
        $id_trx = @$_REQUEST['id_trx'];
        $sessionid = @$_REQUEST['sessionid'];

        $data =  $this->m_file->join('dokumen', 'file.id_dok = dokumen.id_dok', 'left')
            ->select('file.*, dokumen.nm_dok')
            ->where('sessionid', $sessionid)
            ->where('file.id_trx', $id_trx)
            ->findAll();

        echo json_encode(array('data' => $data));
    }

    public function get_token_api($gdc = null)
    {
        $url = 'https://api-meetmax.xcally.com/api/v1/accounts/login';
        $data = array('email' => $gdc->email_gdc, 'password' => $gdc->password);

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            ),
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        if ($result === FALSE) {
            /* Handle error */
            return false;
        } else {

            $result = json_decode($result);
            return @$result->access_token;
        }
    }

    public function start_record()
    {
        $id_trx = $_REQUEST['id_trx'];

        $data = $this->m_transaksi->find($id_trx);

        if (!empty($data) && empty($data['recordingid'])) {

            $gdc = $this->m_gdc->find($data['id_gdc']);

            if (get_tipe() == 'local') {

                $token = $this->get_token_api($gdc);
                $token_str = "Authorization: Bearer " . $token . "\r\n" .
                    "x-api-key: " . $gdc['apikey'] . "\r\n";
            } else {
                $token_str = "X-APIKEY: " . $gdc['apikey'] . "\r\n";
            }

            // echo $data['sessionid'] .'<br>';
            $url = 'https://api-vidaoo.xcally.com/api/v1/recordings';
            $data = json_encode(array('session_id' => $data['sessionid']));


            // use key 'http' even if you send the request to https://...
            // echo $token.'<br>';
            // echo $token_x.'<br>';

            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/json\r\n" .
                        $token_str,
                    'method'  => 'POST',
                    'content' => $data
                ),
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false
                )
            );
            $context  = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            if ($result === FALSE) {
                /* Handle error */
                echo json_encode(array('status' => 'gagal'));
            } else {
                $result = json_decode($result);

                $this->m_transaksi->update($id_trx, array('recordingid' => $result->id));
                echo json_encode(array('status' => 'sukses'));
            }
        }
    }

    function grafik_per_bulan()
    {
        $incoming = (int)$this->m_transaksi->select('count(*) as jml')
            ->where('id_jenis', 1)
            ->first()['jml'];

        $received = (int)$this->m_transaksi->select('count(*) as jml')
            ->where('id_jenis', 2)
            ->first()['jml'];

        $receiveds = (int)$this->m_transaksi->select('count(*) as jml')
            ->where('id_jenis', 3)
            ->first()['jml'];

        echo json_encode(array('incoming' => $incoming, 'received' => $received, 'receiveds' => $receiveds));
    }

    function grafik_all_service()
    {

        for ($i = 1; $i <= 12; $i++) {

            $data[] = (int)$this->m_transaksi->select('count(*) as jml')
                ->where('YEAR(tanggal) = ' . date('Y') . ' AND MONTH(tanggal) = ' . $i . '')
                ->first()['jml'];


            $kategori[] = date('M Y', strtotime(date('Y-' . $i . '')));
        }

        echo json_encode(array('data' => $data, 'kategori' => $kategori));
    }



    public function export_excel_csat()
    {
        $this->load->library('excel');


        //==========inisialisasi sheet==========\\
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Report CSAT');

        //==========buat header excel atau isi value di excel==========\\
        // $this->excel->getActiveSheet()->mergeCells('A1:I1');
        $this->excel->getActiveSheet()->mergeCells('A2:J2');
        $this->excel->getActiveSheet()->setCellValue('A2', 'REPORT CSAT');

        $this->excel->getActiveSheet()->setCellValue('A4', 'Date');
        $this->excel->getActiveSheet()->setCellValue('B4', 'Session ID');
        $this->excel->getActiveSheet()->setCellValue('C4', 'Driver');
        $this->excel->getActiveSheet()->setCellValue('D4', 'Driver Type');
        $this->excel->getActiveSheet()->setCellValue('E4', 'Service');
        $this->excel->getActiveSheet()->setCellValue('F4', 'Agent');
        $this->excel->getActiveSheet()->setCellValue('G4', 'KiosK');
        $this->excel->getActiveSheet()->setCellValue('H4', 'Received');
        $this->excel->getActiveSheet()->setCellValue('I4', 'Finish');
        $this->excel->getActiveSheet()->setCellValue('J4', 'CSAT');
        // $this->excel->getActiveSheet()->setCellValue('K4','CSAT');
        // $this->excel->getActiveSheet()->setCellValue('L4','Status');

        //==========masukan data dari table==========\\

        $this->db->select('sessionid, id_trx, tanggal, nm_driver, wkt_upload, wkt_mulai, wkt_selesai, nm_layanan, nm_gdc, lokasi, id_csat, transaksi.sts_trx, nm_cs, nm_jenis');

        $filter = $this->session->get('filter');
        // $tgl_awal = changeDateFormat('database', @$filter['tanggal_awal']);
        // $tgl_akhir = changeDateFormat('database',@ $filter['tanggal_akhir']);

        if (!empty($filter['tanggal_awal']) && !empty($filter['tanggal_akhir'])) {
            $this->db->where('tanggal >=', $tgl_awal . ' 00:00:00');
            $this->db->where('tanggal <=', $tgl_akhir . ' 23:59:59');
        }

        if (!empty($filter['id_layanan'])) {
            $this->db->where('transaksi.id_layanan', $filter['id_layanan']);
        }

        if (!empty($filter['id_cs'])) {
            $this->db->where('transaksi.id_cs', $filter['id_cs']);
        }

        // $id_cs =$this->user->id_cs;

        // $this->db->where('id_cs', $id_cs);
        $this->db->join('cs', 'cs.id_cs = transaksi.id_cs', 'left');
        $this->db->join('layanan', 'transaksi.id_layanan = layanan.id_layanan', 'left');
        $this->db->join('gdc', 'transaksi.id_gdc = gdc.id_gdc', 'left');
        $this->db->join('jenis_driver', 'transaksi.id_tipe = jenis_driver.id_jenis', 'left');
        $data = $this->db->get($this->table)->result();
        // print_r($data);exit;
        $i = 5;
        foreach ($data as $t) {
            // if($t->sts_trx == 1){
            //     $sts_trx = 'Waiting';
            // }
            // elseif($t->sts_trx == 2){
            //     $sts_trx = 'On Service';
            // }
            // elseif($t->sts_trx == 3){
            //     $sts_trx = 'Finish';
            // }
            // elseif($t->sts_trx == 4){
            //     $sts_trx = 'Batal';
            // }
            // else{
            //     $sts_trx = '-';
            // }

            $this->excel->getActiveSheet()->setCellValue('A' . $i, empty($t->tanggal) ? '-' : $t->tanggal);
            $this->excel->getActiveSheet()->setCellValue('B' . $i, empty($t->sessionid) ? '-' : $t->sessionid);
            $this->excel->getActiveSheet()->setCellValue('C' . $i, empty($t->nm_driver) ? '-' : $t->nm_driver);
            $this->excel->getActiveSheet()->setCellValue('D' . $i, empty($t->nm_jenis) ? '-' : $t->nm_jenis);
            $this->excel->getActiveSheet()->setCellValue('E' . $i, empty($t->nm_layanan) ? '-' : $t->nm_layanan);
            $this->excel->getActiveSheet()->setCellValue('F' . $i, empty($t->nm_cs) ? '-' : $t->nm_cs);
            $this->excel->getActiveSheet()->setCellValue('G' . $i, empty($t->nm_gdc) ? '-' : $t->nm_gdc);
            $this->excel->getActiveSheet()->setCellValue('H' . $i, empty($t->wkt_mulai) ? '-' : $t->wkt_mulai);
            $this->excel->getActiveSheet()->setCellValue('I' . $i, empty($t->wkt_selesai) ? '-' : $t->wkt_selesai);
            $this->excel->getActiveSheet()->setCellValue('J' . $i, empty($t->id_csat) ? '-' : $t->id_csat);
            // $this->excel->getActiveSheet()->setCellValue('K'.$i,empty($t->id_csat) ? '-' : $t->id_csat);
            // $this->excel->getActiveSheet()->setCellValue('L'.$i,empty($sts_trx) ? '-' : $sts_trx);

            $i++;
        }

        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );

        $urut = $i - 1;

        $this->excel->getActiveSheet()->getStyle('A4:J' . $urut)->applyFromArray($styleArray);
        unset($styleArray);
        //==========style header==========\\
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(35);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        // $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(15);

        $this->excel->getActiveSheet()->getStyle('A4:J4')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A4:J4')->getAlignment()->setHorizontal(PHPExcel_style_alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A4:J' . $i)->getAlignment()->setHorizontal(PHPExcel_style_alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_style_alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_style_alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('A4:J4')->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => '007024')
                )
            )
        );



        //==========download file generate==========\\
        $filename = 'Report_CSAT_' . date('dmY') . '.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age= 0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');
    }
}
