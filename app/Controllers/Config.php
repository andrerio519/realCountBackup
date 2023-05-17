<?php

namespace App\Controllers;

use App\Models\ConfigModel;

class Config extends BaseController
{
    protected $config;
    public function __construct()
    {
        $this->config = new ConfigModel();
    }
    public function index()
    {
        if (session()->get('level') != "master admin") {
            return redirect()->to(base_url('admin'));
        }
        $data = [
            "title" => "Config",
            "config" => $this->config->getConfig(),
            'validation' => \Config\Services::validation(),
        ];
        return view('admin/configView', $data);
    }

    public function save()
    {
        //validation
        if (!$this->validate([
            'logo' => [
                'rules' => 'max_size[logo,2048]|is_image[logo]|mime_in[logo,image/jpeg,image/jpg,image/png]',
                'errors' => [
                    'max_size' => 'Ukuran file maks 2 MB',
                    'is_image' => 'File bukan gambar',
                    'mime_in' => 'File harus jpeg/jpg/png'
                ]
            ]
        ])) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->to(base_url("config"))->withInput();
        }

        $logo = $this->request->getFile('logo');
        $nama_partai = $this->request->getVar('nama_partai');

        // jika file tidak diupdate
        if ($logo->getError() == 4) {
            $namaFile = $this->request->getVar('logoLama');
        } else {
            // ambil ekstensi file
            $typeFile = $logo->guessExtension();

            // buat nama file baru
            $namaFile = 'logo_' . strtolower(str_replace(' ', '_', $nama_partai)) . '.' . $typeFile;



            // hapus file lama jika ada
            $logoLama = $this->request->getVar('logoLama');
            if (!empty($logoLama)) {
                $path = FCPATH . 'assets/dist/img/' . $logoLama;
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            // simpan file baru
            $logo->move('./assets/dist/img/', $namaFile);
        }

        $no_urut_partai = $this->request->getVar("no_urut");
        $nama_aplikasi = $this->request->getVar("nama_aplikasi");

        $this->config->save([
            'id_config' => 1,
            'nama_aplikasi' => esc($nama_aplikasi),
            'nama_partai' => esc($nama_partai),
            'no_urut_partai' => esc($no_urut_partai),
            'logo' => esc($namaFile),
            'sesi_daftar' => esc($this->request->getVar('sesi_daftar')),
            'sesi_vote' => esc($this->request->getVar('sesi_vote'))
        ]);

        session()->setFlashdata('pesan', 'Data telah diubah');
        return redirect()->to(base_url("config"));
    }
}
