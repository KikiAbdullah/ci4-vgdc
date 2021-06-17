<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Monitoring extends BaseController
{
    public function index()
    {
        // lakukan validasi
        $validation =  \Config\Services::validation();
        $isDataValid = $validation->withRequest($this->request)->run();

        $filter = $this->request->getPost();
        $this->session->set('filter', $filter);
        $data['filter'] = $this->session->get('filter');

        $data['log_gdc'] = $this->m_log_gdc->findAll();

        return view('monitoring/index', $data);
    }
}
