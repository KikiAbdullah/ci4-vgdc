<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;
use Irsyadulibad\DataTables\DataTables;

class Update extends AdminController
{
    function __construct()
    {
        parent::__construct();
        $this->form_validation = \Config\Services::validation();
    }

    public function index()
    {

        if (empty($this->user)) {
            return redirect()->to('/login');
        }

        $data['title'] = $this->title;

        $data['menu_list'] =  $this->m_menu->where('jenis', 1)->orderBy('tipe', 'asc')->findAll();
        $data['akses']  = $this->akses;

        return view('update/index', $data);
    }

    function get_data()
    {
        return DataTables::use('upd')
            ->orderBy('tgl_update', 'desc')
            ->make(true);
    }

    function do_tambah()
    {
        $data = @$this->request->getPost();
        // $data = $this->security->xss_clean($data);
        if ($data) {
            $files = $this->request->getFile('attach');

            if (!empty($files)) {
                $fileName = $files->getRandomName();
                $save = $files->move(ROOTPATH . 'update/fileupdate', $fileName);

                if (!$save) {
                    $this->session->setFlashdata('msg', err_msg('Upload File gagal.'));
                } else {
                    $attach = $fileName;
                    $id_app = $data['id_app'];
                    $nm_app = $data['nm_app'];
                    $ver = $data['ver'];

                    $param = array(
                        'id_user'   => $this->user['id_user'],
                        'id_app'    => $id_app,
                        'nm_app'    => $nm_app,
                        'version'   => $ver,
                        'attach'    => $attach,
                        'tgl_update' => date('Y-m-d H:i:s')
                    );
                    $this->m_upd->insert($param);
                    $this->session->setFlashdata('msg', succ_msg('Berhasil mengupload file update'));
                }
            } else {
                $this->session->setFlashdata('msg', err_msg('File tidak ditemukan.'));
            }
        } else {
            $this->session->setFlashdata('msg', err_msg('File tidak ditemukan.'));
        }
        return redirect()->to('update');
    }

    function hapus($id = NULL)
    {
        if (!$id) throw PageNotFoundException::forPageNotFound();

        $data = $this->m_upd->find(decode($id));

        if (empty($data)) {
            throw PageNotFoundException::forPageNotFound();
        }

        if ($data['attach']) {
            $path_file = ROOTPATH . 'update/fileupdate/' . $data['attach'];
            @unlink($path_file);
        }

        $del_data = $this->m_upd->delete(decode($id));
        if ($del_data) {
            $this->session->setFlashdata('msg', succ_msg('Data Update berhasil dihapus.'));
        } else {
            $this->session->setFlashdata('msg', err_msg('Gagal menghapus.'));
        }

        return redirect()->to('update');
    }
}
