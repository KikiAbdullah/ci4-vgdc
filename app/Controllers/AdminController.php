<?php

namespace App\Controllers;

use App\Models\Cs;
use App\Models\Dokumen;
use App\Models\DokUpload;
use App\Models\Driver;
use App\Models\File;
use App\Models\Gdc;
use App\Models\GdcTemp;
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
use App\Models\UserTemp;

class AdminController extends BaseController
{
    public function __construct()
    {
        // load model
        $this->m_cs = new Cs();
        $this->m_dokumen = new Dokumen();
        $this->m_dok_upload = new DokUpload();
        $this->m_driver = new Driver();
        $this->m_file = new File();
        $this->m_gdc = new Gdc();
        $this->m_gdc_temp = new GdcTemp();
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
        $this->m_user_temp = new UserTemp();

        $this->uri = new \CodeIgniter\HTTP\URI();


        $this->user = session()->get('user_login_vgdc');

        $link = explode('/', uri_string(true));

        @$id_menu = $this->m_menu->where('link', $link[0])->first()['id_menu'];

        @$this->akses = $this->user['hak_akses'][$id_menu];
    }
}
