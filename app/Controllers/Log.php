<?php

namespace App\Controllers;

use App\Controllers\BaseController;

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
}
