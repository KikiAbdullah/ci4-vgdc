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
use Hermawan\DataTables\DataTable;

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

			$doc = $this->m_dokumen->join('tipe_dokumen', 'tipe_dokumen.id_tipe_dok = dokumen.id_tipe_dok', 'left')->findAll();
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
			$data = $this->m_gdc->find($id_gdc);

			return json_encode(array('data' => $data));
		} else {
			abort(404);
		}
	}

	public function transaksi_ns()
	{
		$hayo = @$_REQUEST['hayo'];

		if (decode($hayo) == hayo()) {
			$no_antrian = $this->m_transaksi->where('tanggal', date('Y-m-d'))->select('max(no_antrian)', 'no_antrian')->first()['no_antrian'];


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

			$param = array(
				'nm_driver' 		=> $nm_driver,
				'id_gdc'		=> $id_gdc,
				'id_tipe'		=> $id_tipe,
				'id_jenis'		=> $id_jenis,
				'id_layanan'	=> $id_layanan,
				'tanggal' => date('Y-m-d'),
				'wkt_upload'  => date('H:i:s'),
				'no_antrian' => $no_antrianbaru,
				'sts_trx' => 1,
			);
			$this->m_transaksi->insert($param);

			$id_trx =  $this->m_transaksi->getInsertID();

			if (!empty(@$_FILES['file']['name'])) {

				foreach ($_FILES['file']['name'] as $key => $value) {
					$_FILES['file[]']['name'] = $_FILES['file']['name'][$key];
					$_FILES['file[]']['type'] = $_FILES['file']['type'][$key];
					$_FILES['file[]']['tmp_name'] = $_FILES['file']['tmp_name'][$key];
					$_FILES['file[]']['error'] = $_FILES['file']['error'][$key];
					$_FILES['file[]']['size'] = $_FILES['file']['size'][$key];

					$file_name = $_FILES['file']['name'][$key];
					if (!empty($file_name)) {

						$config['upload_path'] = 'uploads/file';
						$config['allowed_types'] = '*';

						$new_name = $file_name;
						$config['file_name'] = $new_name;

						$this->load->library('upload', $config);
						$this->upload->initialize($config);

						if (!$this->upload->do_upload('file[]')) {

							return json_encode(array('status' => 'gagal', 'msg' => 'upload file gagal'));
						} else {
							$dataFile  = $this->upload->data();
							$file_name = $dataFile['file_name'];

							$param = array(
								'id_trx' => $id_trx,
								'id_dok' => $id_dok[$key],
								'attach' => $file_name
							);
							$this->m_file->insert($param);
						}
					} else {
						return json_encode(array('status' => 'gagal', 'msg' => 'File Not Found'));
					}
				}
			}

			$data = $this->m_transaksi->where('tanggal', date('Y-m-d'))->orderBy('id_trx', 'desc')->first();

			return json_encode(array('status' => 'sukses', 'data' => $data));
		} else {
			abort(404);
		}
	}


	// data tabel handling	
	public function get_list_report()
	{
		// INI BELUM
		$this->m_transaksi->select('transaksi.*,  transaksi.id_trx, layanan.nm_layanan,  timediff(wkt_selesai, wkt_mulai) as waktu, gdc.lokasi, gdc.nm_gdc, jenis_driver.nm_jenis, tipe_driver.nm_tipe, csat.gambar, nm_driver, tanggal, tipe_driver.nm_tipe, wkt_mulai, wkt_selesai, sessionid, sts_trx ', false)
			->join('layanan', 'transaksi.id_layanan = layanan.id_layanan', 'left')
			->join('gdc', 'transaksi.id_gdc = gdc.id_gdc', 'left')
			->join('jenis_driver', 'transaksi.id_jenis = jenis_driver.id_jenis', 'left')
			->join('tipe_driver', 'transaksi.id_tipe = tipe_driver.id_tipe', 'left')
			->join('csat', 'transaksi.id_csat = csat.id_csat', 'left')
			->orderBy('tanggal', 'desc')
			->orderBy('wkt_mulai', 'desc');

		$filter = $this->session->get('filter');
		if (!empty($filter['tanggal_awal']) && !empty($filter['tanggal_akhir'])) {
			$where_filter = array(
				'date(tanggal) >=' => date('Y-m-d', strtotime($filter['tanggal_awal'])),
				'date(tanggal) <=' => date('Y-m-d', strtotime($filter['tanggal_akhir']))
			);
			$this->m_transaksi->where($where_filter);
		}

		if (!empty($filter['id_layanan'])) {
			$where_id = array(
				'transaksi.id_layanan' => $filter['id_layanan']
			);
			$this->m_transaksi->where($where_id);
		}

		$data = $this->m_transaksi->findAll();

		return DataTable::of($data)
			->toJson();
	}

	// view document
	public function view_dokumen()
	{
		$id_trx = @$_REQUEST['id_trx'];

		$data = @$this->m_file->where('id_trx', $id_trx)->first()['id_trx'];

		if (!empty($data)) {
			$db_data = @$this->m_file->join('dokumen', 'file.id_dok = dokumen.id_dok', 'left')
				->where('id_trx', $data)->findAll();

			foreach ($db_data as $key => $value) {
				$image = base_url('uploads/file') . '/' . $value['attach'];
				$respone[] = array(
					'name' => $value['nm_dok'],
					'image' => $image
				);
			}

			echo stripslashes(json_encode(array('status' => '200', 'message' => 'OK', 'id_trx' => $value['id_trx'], 'data' => $respone), JSON_UNESCAPED_SLASHES));
		} else {

			return json_encode(array('status' => '401', 'message' => 'Session Id Not Found', 'data' => $data));
		}
	}

	// tombol view document
	function button($param)
	{
		//if(!empty($this->akses[2])){
		$html = '<input class="id_trx" type="hidden" name="id_trx" value="' . $param . '"/><button class="view_document"  style="border : 0!important; background-color : transparent!important;" type="submit"><img src="' . base_url('assets/icon_mekar.png') . '" style="width: 30px;"></button></form>';
		//}
		return $html;
	}
	// tombol finish
	function buttons($param)
	{
		$html = '<input class="id_trx" type="hidden" name="id_trx" value="' . $param . '"/>
					<button class="btn end_session" style="background-color : #009a3c;color :azure; border-radius : 5px;">Selesai </button>';

		return $html;
	}

	public function updates_selesai()
	{
		$id_trx = @$_REQUEST['id_trx'];

		$sts = 2;

		$data = @$this->m_transaksi->where('id_trx', $id_trx)
			->where('sts_trx', $sts)
			->first();

		$idtx = $data['id_trx'];
		if (!empty($data)) {
			// INI BELUM
			// $param =  [
			// 	'sts_trx' => 3,
			// 	'wkt_selesai' => date('H:i:s')
			// ];
			// $update = $this->m_transaksi->update($idtx, $param);

			echo json_encode(array('status' => 'sukses', 'message' => 'OK', 'data_id_trx' => $idtx));
		} else {
			echo json_encode(array('status' => '404', 'message' => 'Not Found'));
		}
	}


	// ----------------------------------------------------------------------------------------------------------------------------------

	public function get_service()
	{
		$data = $this->m_layanan->where('status', 'Y')->orderBy('id_layanan', 'asc')->findAll();
		return json_encode(array('layanan' => $data));
	}

	public function get_tipe()
	{
		$data = $this->m_tipe_driver->where('status', 'Y')->orderBy('id_tipe', 'asc')->findAll();
		return json_encode(array('data' => $data, 'jumlah' => count($data)));
	}

	public function get_jenis()
	{
		$data = $this->m_jenis_driver->where('status', 'Y')->orderBy('id_jenis', 'asc')->findAll();
		return json_encode(array('data' => $data, 'jumlah' => count($data)));
	}

	public function get_list_dokumen()
	{
		try {
			$id_dok = @$_REQUEST['id_dok'];
			$data = $this->m_dokumen->where('status', 'Y')->orderBy('id_dok', 'asc')->findAll();

			return json_encode(array('data' => $data));
		} catch (\Throwable $th) {
			return json_encode(array('status' => 'gagal'));
		}
	}

	public function get_list_layanan()
	{
		$id_tipe = @$_REQUEST['id_tipe'];
		$id_jenis = @$_REQUEST['id_jenis'];
		$hayo = @$_REQUEST['hayo'];
		if (decode($hayo) == hayo()) {


			$data = $this->m_dok_upload->select('dok_upload.id_layanan', 'layanan.nm_layanan')
				->join('layanan', 'layanan.id_layanan = dok_upload.id_layanan', 'left')
				->where('dok_upload.id_tipe', $id_tipe)
				->where('dok_upload.id_jenis', $id_jenis)
				->orderBy('dok_upload.id_layanan', 'asc')
				->findAll();


			return json_encode(array('data' => $data, 'jumlah' => count($data)));
		} else {
			abort(404);
		}
	}

	public function get_list_dok()
	{
		$id_layanan = @$_REQUEST['id_layanan'];
		$hayo = @$_REQUEST['hayo'];
		if (decode($hayo) == hayo()) {
			$d = $this->m_layanan->find($id_layanan)['id_dok'];
			$data = $this->m_dokumen->whereIn('id_dok', explode(',', $d))->findAll();
			return json_encode(array('data' => $data, 'jumlah' => count($data)));
		} else {
			abort(404);
		}
	}

	public function get_tipe_dok()
	{
		$id_dok = @$_REQUEST['id_dok'];
		$hayo = @$_REQUEST['hayo'];
		if (decode($hayo) == hayo()) {
			$data = $this->m_dokumen->join('tipe_dokumen', 'tipe_dokumen.id_tipe_dok = dokumen.id_tipe_dok', 'left')
				->where('id_dok', $id_dok)
				->select('dokumen.id_tipe_dok', 'tipe_dokumen.ket')
				->findAll();

			return json_encode(array('data' => $data, 'jumlah' => count($data)));
		} else {
			abort(404);
		}
	}

	public function login_driver()
	{
		$email = @$_REQUEST['email'];
		$password = @$_REQUEST['password'];
		$data = $this->m_driver->where('email_drv', $email)->where('pass_drv', md5($password))->first();
		if (!empty($data)) {
			return json_encode(array('data' => $data, 'status' => 'sukses'));
		} else {
			return json_encode(array('status' => 'gagal', 'msg' => 'Username atau password yang anda masukkan salah'));
		}
	}

	public function daftar_driver()
	{
		$email = @$_REQUEST['email'];
		$nama = @$_REQUEST['nama'];
		$password = @$_REQUEST['password'];

		$ada_email = $this->m_driver->where('email_drv', $email)->first();
		if (empty($ada_email)) {
			$param = [
				"nm_drv" => $nama,
				"email_drv" => $email,
				"pass_drv" => md5($password),
				"status" => 'Y'
			];
			$driver = $this->m_driver->insert($param);

			return json_encode(array('status' => 'sukses', 'msg' => 'Pendaftaran Sukses'));
		} else {
			return json_encode(array('status' => 'gagal', 'msg' => 'Email sudah pernah digunakan'));
		}
	}

	public function transaksi()
	{
		$hayo = @$_REQUEST['hayo'];

		if (decode($hayo) == hayo()) {
			$no_antrian = $this->m_transaksi->select('max(no_antrian) as no_antrian')->where('tanggal', date('Y-m-d'))->first()['no_antrian'];

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
			$sessionid = @$_REQUEST['sessionid'];

			$param = [
				"nm_driver" => $nm_driver,
				"id_gdc" => $id_gdc,
				"id_tipe" => $id_tipe,
				"id_jenis" => $id_jenis,
				"id_layanan" => $id_layanan,
				"tanggal" => date('Y-m-d'),
				"wkt_upload" => date('H:i:s'),
				"no_antrian" => $no_antrianbaru,
				"sts_trx" => 1,
			];
			$trx = $this->m_transaksi->insert($param);

			$id_trx = $trx->getInsertId();

			// INI BELUM

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

			//                 $this->db->insert('file', array('sessionid' => $sessionid, 'id_trx' => $id_trx, 'id_dok' => $id_dok[$key], 'attach' => $file_name));
			//             }
			//         } else {
			//             return json_encode(array('status' => 'gagal', 'msg' => 'File tidak ditemukan '));
			//         }
			//     }
			// }

			$data = $this->m_transaksi->where('tanggal', date('Y-m-d'))
				->orderBy('id_trx', 'desc')->first();

			return json_encode(array('status' => 'sukses', 'data' => $data));
		} else {
			abort(404);
		}
	}

	public function upload_file()
	{
		$id_trx = @$_REQUEST['id_trx'];
		$id_dok = @$_REQUEST['id_dok'];
		$sessionid = @$_REQUEST['sessionid'];
		$hayo = @$_REQUEST['hayo'];

		if (decode($hayo) == hayo()) {

			$file_name = $_FILES['file']['name'];

			if (!empty($file_name)) {

				$config['upload_path'] = 'uploads/file';
				$config['allowed_types'] = '*';

				$new_name = $file_name;
				$config['file_name'] = $new_name;

				$this->load->library('upload', $config);
				$this->upload->initialize($config);

				if (!$this->upload->do_upload('file')) {

					return json_encode(array('status' => 'gagal', 'msg' => 'upload file gagal'));
				} else {
					$dataFile  = $this->upload->data();
					$file_name = $dataFile['file_name'];

					//$this->encrypt_file("./uploads/file/".$file_name, "./uploads/file/".$file_name, 'jos');

					$detail = $this->m_file->where('id_trx', $id_trx)
						->where('id_dok', $id_dok)
						->where('sessionid', $sessionid)
						->first();

					if (!empty($detail)) {
						$this->m_file->where('id_trx', $id_trx)
							->where('id_dok', $id_dok)
							->where('sessionid', $sessionid);
						$this->m_file->update(array('attach' => $file_name));
					} else {
						$param = [
							'id_trx' => $id_trx,
							'id_dok' => $id_dok,
							'sessionid' => $sessionid,
							'attach' => $file_name,
						];

						$file = $this->m_file->insert($param);
					}

					if ($file) {
						return json_encode(array('status' => 'sukses'));
					} else {
						return json_encode(array('status' => 'gagal', 'msg' => 'gagal update data'));
					}
				}
			} else {
				return json_encode(array('status' => 'gagal', 'msg' => 'File tidak ditemukan '));
			}
		} else {
			abort(404);
		}
	}

	public function coba()
	{
		// $param = $this->input->post('files');
		$param = '1-20200503-1-IVAN-KTP-PERUBAHAN_INSENTIF1.jpg';
		// seharuse dari 
		$file_name = $param;
		// $file_name = $this->upload();
		$dekrip_file = $this->decrypt_file("./uploads/file/" . $file_name, 'jos');
		$path = "./uploads/file/" . $file_name;
		$ext = pathinfo($path, PATHINFO_EXTENSION);
		header("Content-Type: image/.jpg");
		fpassthru($dekrip_file);
	}

	public function encrypt_file($file, $destination, $passphrase)
	{
		$handle = fopen($file, "rb") or die("could not open the file");
		$contents = fread($handle, filesize($file));
		fclose($handle);

		$iv     = substr(md5("\x18\x3C\x58" . $passphrase, true), 0, 8);
		$key     = substr(md5("\x2D\xFC\xD8" . $passphrase, true) . md5("\x2D\xFC\xD8" . $passphrase, true), 0, 24);
		$opts     = array('iv' => $iv, 'key' => $key);
		$fp     = fopen($destination, 'wb') or die("Tidak dapat membuka file untuk menulis");
		stream_filter_append($fp, 'mcrypt.tripledes', STREAM_FILTER_WRITE, $opts);
		fwrite($fp, $contents) or die('Tidak dapat menulis ke file');
		fclose($fp);
	}

	public function decrypt_file($file, $passphrase)
	{
		$iv     = substr(md5("\x18\x3C\x58" . $passphrase, true), 0, 8);
		$key     = substr(md5("\x2D\xFC\xD8" . $passphrase, true) . md5("\x2D\xFC\xD8" . $passphrase, true), 0, 24);
		$opts     = array('iv' => $iv, 'key' => $key);
		$fp     = fopen($file, 'rb');
		stream_filter_append($fp, 'mdecrypt.tripledes', STREAM_FILTER_READ, $opts);
		return $fp;
	}

	public function tembak_link()
	{
		$link = @$_REQUEST['link'];
		$id_trx = @$_REQUEST['id_trx'];
		$sessionid = @$_REQUEST['sessionid'];
		$hayo = @$_REQUEST['hayo'];

		if (decode($hayo) == hayo()) {
			$param = [
				'link' => $link,
				'sessionid' => $sessionid
			];
			$this->m_transaki->update($id_trx, $param);
		} else {
			abort(404);
		}
	}

	public function cek_call()
	{
		$id_trx = @$_REQUEST['id_trx'];
		$hayo = @$_REQUEST['hayo'];

		if (decode($hayo) == hayo()) {

			$data = $this->m_transaksi->find($id_trx);

			return json_encode(array('data' => $data));
		} else {
			abort(404);
		}
	}

	public function cek_antrian()
	{
		$hayo = @$_REQUEST['hayo'];

		if (decode($hayo) == hayo()) {
			$data = $this->m_transaksi->where('sts_trx', 1)
				->where('tanggal', date('Y-m-d'))
				->findAll();

			return json_encode(array('jumlah' => count($data)));
		} else {
			abort(404);
		}
	}

	function waktu_conv_his($waktu)
	{
		if (($waktu > 0) and ($waktu < 60)) {
			$lama = "00:00:" . str_pad($waktu, 2, "0", STR_PAD_LEFT);
			return $lama;
		}
		if (($waktu > 60) and ($waktu < 3600)) {
			$detik = fmod($waktu, 60);
			$menit = $waktu - $detik;
			$menit = $menit / 60;
			$lama = "00:" . str_pad($menit, 2, "0", STR_PAD_LEFT) . ":" . str_pad($detik, 2, "0", STR_PAD_LEFT);
			return $lama;
		} elseif ($waktu > 3600) {
			$detik = fmod($waktu, 60);
			$tempmenit = ($waktu - $detik) / 60;
			$menit = fmod($tempmenit, 60);
			$jam = ($tempmenit - $menit) / 60;
			$lama = str_pad($jam, 2, "0", STR_PAD_LEFT) . ":" . str_pad($menit, 2, "0", STR_PAD_LEFT) . ":" . str_pad($detik, 2, "0", STR_PAD_LEFT);
			return $lama;
		}
	}

	public function get_statistik()
	{
		$hayo = @$_REQUEST['hayo'];

		if (decode($hayo) == hayo()) {
			//total handling
			$sts_trx = 3;
			$data['total_driver'] = $this->m_transaksi->select('count(distinct(id_trx)) as total_driver')
				->where('sts_trx', $sts_trx)->first()['total_driver'];

			//Average Handling Time
			$avg = $this->m_transaksi->where('sts_trx', $sts_trx)
				->select('avg(timestampdiff(SECOND, wkt_mulai, wkt_selesai)) as avg')->first()['avg'];


			$data['avg_time'] = $this->waktu_conv_his(ceil($avg));


			if (empty($data['avg_time'])) {
				$data['avg_time'] = '00:00:00';
			}

			$avg_rating = $this->m_transaksi->select('avg(id_csat) as avg')->where('sts_trx', 3)->first()['avg'];
			$data['avg_rating'] = number_format($avg_rating, 2);

			return json_encode(array('status' => 'sukses', 'data' => $data));
		} else {
			abort(404);
		}
	}

	public function get_link()
	{
		$sts = 1;
		$data = $this->m_transaksi->where('tanggal', date('Y-m-d'))
			->where('sts_trx', $sts)
			->orderBy('wkt_upload', 'asc')
			->first();

		return json_encode(array('status' => 'sukses', 'data' => $data));
	}


	public function get_list_queue()
	{
		$hayo = @$_REQUEST['hayo'];

		if (decode($hayo) == hayo()) {
			// Queue
			$sts = 1;
			$data['queue'] = $this->m_transaksi->select('transaksi.*', 'layanan.nm_layanan', 'concat(tanggal, " ", wkt_upload ) as waktu', 'gdc.lokasi', 'jenis_driver.nm_jenis', 'tipe_driver.nm_tipe')
				->join('gdc', 'transaksi.id_gdc=gdc.id_gdc', 'left')
				->join('jenis_driver', 'transaksi.id_jenis=jenis_driver.id_jenis', 'left')
				->join('tipe_driver', 'transaksi.id_tipe=tipe_driver.id_tipe', 'left')
				->where('sts_trx', $sts)
				->where('tanggal', date('Y-m-d'))
				->orderBy('wkt_upload', 'asc')->findAll(5);

			//On Service
			$id_cs = @$this->session->get('user')->id_cs;
			$sts = 2;

			$data['on_service'] = $this->m_transaksi->select('transaksi.*', 'layanan.nm_layanan', 'concat(tanggal, " ", wkt_upload ) as waktu', 'gdc.lokasi', 'jenis_driver.nm_jenis', 'tipe_driver.nm_tipe')
				->join('gdc', 'transaksi.id_gdc=gdc.id_gdc', 'left')
				->join('jenis_driver', 'transaksi.id_jenis=jenis_driver.id_jenis', 'left')
				->join('tipe_driver', 'transaksi.id_tipe=tipe_driver.id_tipe', 'left')
				->where('sts_trx', $sts)
				->where('id_cs', $id_cs)
				->where('tanggal', date('Y-m-d'))
				->orderBy('wkt_upload', 'asc')->findAll(5);

			return json_encode(array('status' => 'sukses', 'data' => $data));
		} else {
			abort(404);
		}
	}

	//==========================================================================================================================================================================


	public function get_list_dashboard()
	{
		// INI BELUM
		// $data = $this->m_transaksi->join('layanan', 'transaksi.id_layanan ', '=', ' layanan.id_layanan', 'left')
		// 	->join('gdc', 'transaksi.id_gdc ', '=', ' gdc.id_gdc', 'left')
		// 	->join('jenis_driver', 'transaksi.id_jenis ', '=', ' jenis_driver.id_jenis', 'left')
		// 	->join('tipe_driver', 'transaksi.id_tipe ', '=', ' tipe_driver.id_tipe', 'left')
		// 	->join('csat', 'transaksi.id_csat ', '=', ' csat.id_csat', 'left')
		// 	->where('sts_trx', 1)
		// 	->where('tanggal', date('Y-m-d'))
		// 	->orderBy('tanggal', 'desc')
		// 	->orderBy('wkt_mulai', 'desc')
		// 	->selectRaw('transaksi.*', 'transaksi.id_trx', 'layanan.nm_layanan', 'timediff(wkt_selesai, wkt_mulai) as waktu', 'gdc.lokasi', 'gdc.nm_gdc', 'jenis_driver.nm_jenis', 'tipe_driver.nm_tipe', 'csat.gambar', 'nm_driver', 'tanggal', 'tipe_driver.nm_tipe', 'wkt_mulai', 'wkt_selesai', 'sessionid', 'sts_trx')
		// 	->get();

		// return DataTables::of($data)
		// 	->make(true);
	}


	// API view dokumen


	public function insert_recordid()
	{
		$sessionid = @$_REQUEST['sessionid'];
		$recordid = @$_REQUEST['recordid'];
		$hayo = @$_REQUEST['hayo'];

		if (decode($hayo) == hayo()) {
			$this->m_transaksi->where('sessionid', $sessionid);
			$this->m_transaksi->update(array('recordingid' => $recordid));
		} else {
			abort(404);
		}
	}

	//==========================================================================================================================================================================

	public function update_sts()
	{
		$id_trx =  @$_REQUEST['id_trx'];
		$hayo = @$_REQUEST['hayo'];

		if (decode($hayo) == hayo()) {
			$sts = 1;

			$data = $this->m_transaksi->where('id_trx', $id_trx)
				->where('tanggal', date('Y-m-d'))
				->where('sts_trx', $sts)
				->first();

			if (!empty($data)) {

				$id_cs = @$this->session->get('user')['id_cs'];

				$param = [
					"sts_trx" => 2,
					"wkt_mulai" => date('H:i:s'),
					"id_cs" => $id_cs
				];

				$result = $this->m_transaksi->update($data['id_trx'], $param);

				if ($result) {
					echo json_encode(array('status' => 'sukses', 'data' => $data['id_trx']));
				} else {
					echo json_encode(array('status' => 'gagal', 'msg' =>  'saat akan melakukan panggilan video '));
				}
			} else {
				echo json_encode(array('status' => 'gagal', 'msg' => 'saat akan melakukan panggilan video '));
			}
		} else {
			abort(404);
		}
	}
}
