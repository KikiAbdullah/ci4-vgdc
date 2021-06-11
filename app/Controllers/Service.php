<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Dokumen;
use App\Models\DokUpload;
use App\Models\Driver;
use App\Models\File;
use App\Models\Gdc;
use App\Models\JenisDriver;
use App\Models\Layanan;
use App\Models\Log;
use App\Models\LogGdc;
use App\Models\Menu;
use App\Models\SubJenis;
use App\Models\TipeDriver;
use App\Models\Transaksi;
use App\Models\Upd;
use App\Models\User;
use App\Models\UserRole;

class Service extends BaseController
{
	public function __construct()
	{
		$this->m_dokumen = new Dokumen();
		$this->m_dok_upload = new DokUpload();
		$this->m_driver = new Driver();
		$this->m_file = new File();
		$this->m_gdc = new Gdc();
		$this->m_jenis_driver = new JenisDriver();
		$this->m_layanan = new Layanan();
		$this->m_log = new Log();
		$this->m_log_gdc = new LogGdc();
		$this->m_menu = new Menu();
		$this->m_sub_jenis = new SubJenis();
		$this->m_tipe_driver = new TipeDriver();
		$this->m_transaksi = new Transaksi();
		$this->m_upd = new Upd();
		$this->m_user = new User();
		$this->m_user_role = new UserRole();
	}

	public function get_tipejenis()
	{
		$hayo = @$_REQUEST['hayo'];

		if (decode($hayo) == hayo()) {

			$data = $this->m_tipe_driver->where('status', 'Y')->orderBy('id_tipe', 'asc')->findAll();

			$data1 = $this->m_jenis_driver->where('status', 'Y')->orderBy('id_jenis', 'asc')->findAll();

			echo json_encode(array('tipe' => $data, 'jumlahtipe' => count($data), 'jenis' => $data1, 'jumlahjenis' => count($data1)));
		} else {
			// show_404();
		}
	}

	public function get_tipenew()
	{
		$hayo = @$_REQUEST['hayo'];

		if (decode($hayo) == hayo()) {
			$tipe = $this->m_tipe_driver->where('status', 'Y')->orderBy('id_tipe', 'asc')->findAll();

			$jml = count($tipe);
			$data = [];
			for ($i = 1; $i <= $jml; $i++) {
				$tipe = $this->m_tipe_driver->find($i);

				$d = @$tipe['id_jenis'];

				if (!empty($d)) {
					// belum
					$get_jenis = $this->jenis_driver->where('id_jenis', explode(',', $d))->findAll();

					$data[$i]['jenis'] = @$get_jenis;
					$data[$i]['jumlahjenis'] = @count($data[$i]['jenis']);
				}
			}

			return json_encode(array('tipe' => $data, 'jumlahtipe' => count($data)));
		} else {
			abort(404);
		}
	}

	public function get_subjenis()
	{
		$id_sub = @$_REQUEST['idsub'];
		$hayo = @$_REQUEST['hayo'];

		if (decode($hayo) == hayo()) {
			$where_jenis = [
				'status' => 'Y',
				'id_jenis' => $id_sub
			];
			$d = $this->jenis_driver->where($where_jenis)->first()['id_sub_jenis'];

			// belum
			$get_sub = $this->m_sub_jenis->whereIn('id_sub_jenis', explode(',', $d))->findAll();
			$data = @$get_sub;

			return json_encode(array('data' => $data, 'jumlah' => count($data)));
		} else {
			abort(404);
		}
	}

	public function get_listservice()
	{
		$hayo = @$_REQUEST['hayo'];

		if (decode($hayo) == hayo()) {
			// belum
			$layanan = $this->m_dok_upload->join('layanan', 'layanan.id_layanan = dok_upload.id_layanan', 'left')->orderBy('id_dok_upload', 'asc')->findAll();

			foreach ($layanan as $key => $value) {
				$data[$value['id_tipe']][$value['id_jenis']]['layanan'][] = $value;
				$data[$value['id_tipe']][$value['id_jenis']]['jumlah'] = count($data[$value['id_tipe']][$value['id_jenis']]['layanan']);
			}
			return json_encode(array('service' => $data, 'jumlah' => count($data)));
		} else {
			abort(404);
		}
	}

	public function get_listdoc()
	{
		$hayo = @$_REQUEST['hayo'];

		if (decode($hayo) == hayo()) {
			$dataserv = $this->m_layanan->where('status', 'Y')->orderBy('id_layanan', 'asc')->findAll();

			$jml = count($dataserv);
			$data = [];
			for ($i = 1; $i <= $jml; $i++) {
				$d = $this->m_layanan->find($i)['id_dok'];
				if (!empty($d)) {
					$data[$i]['dokumen'] = @$this->m_dokumen->whereIn('id_dok', explode(',', $d))->findAll();
					$data[$i]['jumlah'] = @count($data[$i]['dokumen']);
				}
			}
			return json_encode(array('thedoc' => $data));
		} else {
			abort(404);
		}
	}

	public function get_doctype()
	{
		$hayo = @$_REQUEST['hayo'];

		if (decode($hayo) == hayo()) {

			$doc = $this->m_dokumen->join('tipe_dokumen', 'tipe_dokumen.id_tipe_dok', 'dokumen.id_tipe_dok', 'left')->findAll();
			foreach ($doc as $key => $value) {
				$data[$value['id_dok']] = $value;
			}
			return json_encode(array('doc' => $data, 'jumlah' => count($data)));
		} else {
			abort(404);
		}
	}

	public function gdc()
	{
		$id_gdc = @$_REQUEST['id_gdc'];
		$hayo = @$_REQUEST['hayo'];

		if (decode($hayo) == hayo()) {
			$data = Gdc::find($id_gdc);

			return json_encode(array('data' => $data));
		} else {
			abort(404);
		}
	}

	public function transaksi_ns()
	{

		$hayo = @$_REQUEST['hayo'];

		if (decode($hayo) == hayo()) {
			$no_antrian = Transaksi::where('tanggal', date('Y-m-d'))->max('no_antrian', 'no_antrian');

			if ($no_antrian != '') {
				$no_antrianbaru = intval($no_antrian) + 1;
			} else {
				$no_antrianbaru    = 1;
			}

			$nm_driver = @$_REQUEST['nm_driver'];
			$id_gdc = @$_REQUEST['id_gdc'];
			$id_layanan = @$_REQUEST['id_layanan'];
			$id_tipe = @$_REQUEST['id_tipe'];
			$id_jenis = @$_REQUEST['id_jenis'];
			$id_dok = @$_REQUEST['id_dok'];
			//$sessionid = @$_REQUEST['sessionid'];

			$insert_trx = new Transaksi;
			$insert_trx->nm_driver    = $nm_driver;
			$insert_trx->id_gdc       = $id_gdc;
			$insert_trx->id_tipe      = $id_tipe;
			$insert_trx->id_jenis     = $id_jenis;
			$insert_trx->id_layanan   = $id_layanan;
			$insert_trx->tanggal      = date('Y-m-d');
			$insert_trx->wkt_upload   = date('H:i:s');
			$insert_trx->no_antrian   = $no_antrianbaru;
			$insert_trx->sts_trx      = 1;
			$insert_trx->save();

			$id_trx = $insert_trx->id();


			// INI BELOM
			// if (!empty(@$_FILES['file']['name'])) {

			//     foreach ($_FILES['file']['name'] as $key => $value) {
			//         $_FILES['file[]']['name'] = $_FILES['file']['name'][$key];
			//         $_FILES['file[]']['type'] = $_FILES['file']['type'][$key];
			//         $_FILES['file[]']['tmp_name'] = $_FILES['file']['tmp_name'][$key];
			//         $_FILES['file[]']['error'] = $_FILES['file']['error'][$key];
			//         $_FILES['file[]']['size'] = $_FILES['file']['size'][$key];

			//         $file_name = $_FILES['file']['name'][$key];
			//         if (!empty($file_name)) {

			//             $config['upload_path'] = 'uploads/file';
			//             $config['allowed_types'] = '*';

			//             $new_name = $file_name;
			//             $config['file_name'] = $new_name;

			//             $this->load->library('upload', $config);
			//             $this->upload->initialize($config);

			//             if (!$this->upload->do_upload('file[]')) {

			//                 return json_encode(array('status' => 'gagal', 'msg' => 'upload file gagal'));
			//             } else {
			//                 $dataFile  = $this->upload->data();
			//                 $file_name = $dataFile['file_name'];

			//                 // enkripsi
			//                 //$this->encrypt_file("./uploads/file/".$file_name, "./uploads/file/".$file_name, 'jos');

			//                 //$this->db->insert('file', array('sessionid'=> $sessionid,'id_trx'=> $id_trx, 'id_dok' => $id_dok[$key], 'attach' => $file_name ));
			//                 $this->db->insert('file', array('id_trx' => $id_trx, 'id_dok' => $id_dok[$key], 'attach' => $file_name));
			//             }
			//         } else {
			//             return json_encode(array('status' => 'gagal', 'msg' => 'File Not Found'));
			//         }
			//     }
			// }

			$data = Transaksi::where('tanggal', date('Y-m-d'))->orderBy('id_trx', 'desc')->first();

			return json_encode(array('status' => 'sukses', 'data' => $data));
		} else {
			abort(404);
		}
	}
}
