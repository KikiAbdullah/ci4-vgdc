<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Csat extends AdminController
{
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
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

        return view('csat/index', $data);
    }

    public function report_csat()
    {
        // lakukan validasi
        $validation =  \Config\Services::validation();
        $isDataValid = $validation->withRequest($this->request)->run();

        $filter = $this->request->getPost();

        $this->session->set('filter', $filter);
        $data['filter'] = $this->session->get('filter');

        $data['cs'] = $this->m_cs->findAll();
        $data['gdc'] = $this->m_gdc->findAll();
        $data['layanan'] = $this->m_layanan->findAll();

        return view('csat/report_csat', $data);;
    }

    public function reset_filter()
    {
        $this->session->remove('filter');
        return redirect()->to('csat');
    }

    public function reset_filter_csat()
    {
        $this->session->remove('filter');
        return redirect()->to('csat/report_csat');
    }

    public function export()
    {
        // ambil data transaction dari database
        $data = $this->get_data_export();


        // panggil class Sreadsheet baru
        $spreadsheet = new Spreadsheet;
        // Buat custom header pada file excel
        //==========inisialisasi sheet==========\\S
        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->getActiveSheet()->setTitle('Report CSAT');

        //==========buat header excel atau isi value di excel==========\\
        // $spreadsheet->getActiveSheet()->mergeCells('A1:I1');
        $spreadsheet->getActiveSheet()->mergeCells('A2:J2');
        $spreadsheet->getActiveSheet()->setCellValue('A2', 'REPORT CSAT');

        $spreadsheet->getActiveSheet()->setCellValue('A4', 'Date');
        $spreadsheet->getActiveSheet()->setCellValue('B4', 'Session ID');
        $spreadsheet->getActiveSheet()->setCellValue('C4', 'Driver');
        $spreadsheet->getActiveSheet()->setCellValue('D4', 'Driver Type');
        $spreadsheet->getActiveSheet()->setCellValue('E4', 'Service');
        $spreadsheet->getActiveSheet()->setCellValue('F4', 'Agent');
        $spreadsheet->getActiveSheet()->setCellValue('G4', 'KiosK');
        $spreadsheet->getActiveSheet()->setCellValue('H4', 'Received');
        $spreadsheet->getActiveSheet()->setCellValue('I4', 'Finish');
        $spreadsheet->getActiveSheet()->setCellValue('J4', 'CSAT');
        // $spreadsheet->getActiveSheet()->setCellValue('K4','CSAT');
        // $spreadsheet->getActiveSheet()->setCellValue('L4','Status');
        // define kolom dan nomor
        $i = 5;

        // tambahkan data transaction ke dalam file excel
        foreach ($data as $t) {
            $spreadsheet->getActiveSheet()->setCellValue('A' . $i, empty($t['tanggal']) ? '-' : $t['tanggal']);
            $spreadsheet->getActiveSheet()->setCellValue('B' . $i, empty($t['sessionid']) ? '-' : $t['sessionid']);
            $spreadsheet->getActiveSheet()->setCellValue('C' . $i, empty($t['nm_driver']) ? '-' : $t['nm_driver']);
            $spreadsheet->getActiveSheet()->setCellValue('D' . $i, empty($t['nm_jenis']) ? '-' : $t['nm_jenis']);
            $spreadsheet->getActiveSheet()->setCellValue('E' . $i, empty($t['nm_layanan']) ? '-' : $t['nm_layanan']);
            $spreadsheet->getActiveSheet()->setCellValue('F' . $i, empty($t['nm_cs']) ? '-' : $t['nm_cs']);
            $spreadsheet->getActiveSheet()->setCellValue('G' . $i, empty($t['nm_gdc']) ? '-' : $t['nm_gdc']);
            $spreadsheet->getActiveSheet()->setCellValue('H' . $i, empty($t['wkt_mulai']) ? '-' : $t['wkt_mulai']);
            $spreadsheet->getActiveSheet()->setCellValue('I' . $i, empty($t['wkt_selesai']) ? '-' : $t['wkt_selesai']);
            $spreadsheet->getActiveSheet()->setCellValue('J' . $i, empty($t['id_csat']) ? '-' : $t['id_csat']);
            // $spreadsheet->getActiveSheet()->setCellValue('K'.$i,empty($t['id_csat']) ? '-' : $t['id_csat']);
            // $spreadsheet->getActiveSheet()->setCellValue('L'.$i,empty($sts_trx) ? '-' : $sts_trx);

            $i++;
        }

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ],
            ],
        ];

        $urut = $i - 1;

        $spreadsheet->getActiveSheet()->getStyle('A4:J' . $urut)->applyFromArray($styleArray);
        unset($styleArray);
        //==========style header==========\\
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(35);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        // $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(15);

        $spreadsheet->getActiveSheet()->getStyle('A4:J4')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A4:J4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A4:J' . $i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A4:J4')->applyFromArray(
            array(
                'fill' => array(
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => '007024',
                    ]
                )
            )
        );

        $filename = 'Report_CSAT_' . date('dmY') . '.Xlsx';
        // download spreadsheet dalam bentuk excel .xlsx
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $filename);
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function get_data_export()
    {

        $filter = $this->session->get('filter');

        $data = $this->m_transaksi->select('sessionid, id_trx, tanggal, nm_driver, wkt_upload, wkt_mulai, wkt_selesai, nm_layanan, nm_gdc, lokasi, id_csat, transaksi.sts_trx, nm_cs, nm_jenis')
            ->join('cs', 'cs.id_cs = transaksi.id_cs', 'left')
            ->join('layanan', 'transaksi.id_layanan = layanan.id_layanan', 'left')
            ->join('gdc', 'transaksi.id_gdc = gdc.id_gdc', 'left')
            ->join('jenis_driver', 'transaksi.id_tipe = jenis_driver.id_jenis', 'left');


        if (!empty($filter['tanggal_awal']) && !empty($filter['tanggal_akhir'])) {
            $data = $data->where('date(tanggal) >=', date('Y-m-d', strtotime(@$filter['tanggal_awal'])))
                ->where('date(tanggal) <=', date('Y-m-d', strtotime(@$filter['tanggal_akhir'])));
        }
        if (!empty($filter['id_layanan'])) {
            $data = $data->where('transaksi.id_layanan', @$filter['id_layanan']);
        }

        if (!empty($filter['id_cs'])) {
            $data = $data->where('transaksi.id_cs', @$filter['id_cs']);
        }

        return $data->findAll();
    }
}
