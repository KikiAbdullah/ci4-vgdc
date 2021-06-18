<?php

namespace App\Controllers;

use App\Models\Cs;
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
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */

class BaseController extends Controller
{
	/**
	 * Instance of the main Request object.
	 *
	 * @var IncomingRequest|CLIRequest
	 */
	protected $request;

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = ['url', 'file', 'My_helpers'];

	/**
	 * Constructor.
	 *
	 * @param RequestInterface  $request
	 * @param ResponseInterface $response
	 * @param LoggerInterface   $logger
	 */
	public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.: $this->session = \Config\Services::session();
		$this->session = \Config\Services::session();

		// load model
		$this->m_cs = new Cs();
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
}
