<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Csat extends BaseController
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

        $data['cs'] = $this->m_cs->findAll();
        $data['gdc'] = $this->m_gdc->findAll();
        $data['layanan'] = $this->m_layanan->findAll();

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
        return redirect()->to('csat/report');
    }

    public function reset_filter_csat()
    {
        $this->session->remove('filter');
        return redirect()->to('csat/report_csat');
    }
}
