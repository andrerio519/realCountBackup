<?php

namespace App\Controllers;

use App\Models\CalegKotaModel;

class Caleg extends BaseController

{
    protected $calegkota;
    public function __construct()
    {
        $this->calegkota = new CalegKotaModel();
    }

    public function index()
    {
        if (session()->get('level') != "master admin") {
            return redirect()->to(base_url('admin'));
        }
        $data = [
            "title" => "Caleg kota",
            "calegkota" => $this->calegkota->findAll(),
            'validation' => \Config\Services::validation(),
        ];
        return view('admin/calegkotaView', $data);
    }
    public function getCaleg()
    {
        $id = $this->request->getVar("id");
        $calegkota = $this->calegkota->getCalegKota($id);
        echo json_encode($calegkota);
    }

    public function createCalegKota()
    {
        // Load the form validation library
        helper(['form']);
        if (!$this->validate([
            'image' => [
                'rules' => 'max_size[image,2048]|is_image[image]|mime_in[image,image/jpeg,image/jpg,image/png]',
                'errors' => [
                    'max_size' => 'Ukuran file maks 2 MB',
                    'is_image' => 'File bukan gambar',
                    'mime_in' => 'File harus jpeg/jpg/png'
                ]
            ]
        ])) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->to(base_url('caleg'))->withInput();
        }

        $no_urut = $this->request->getVar('no_urut');
        $nama = $this->request->getVar('nama');
        $image = $this->request->getFile('image');

        // Check if the selected number has already been used
        $cek_caleg = $this->calegkota
            ->where('no_urutkota', $no_urut)
            ->countAllResults();
        if ($cek_caleg > 0) {
            session()->setFlashdata('gagal', 'Nomor Urut Telah Digunakan');
            return redirect()->to(base_url('caleg'));
        }

        // Move the uploaded image file to the correct directory
        $extension = $image->guessExtension();
        $newName = "{$no_urut}_{$nama}.{$extension}";
        $image->move('./assets/calegImg', $newName);

        // Save the data to the database
        $data = [
            'no_urutkota' => esc($no_urut),
            'nama_calegkota' => esc($nama),
            'image' => $newName,
        ];
        $this->calegkota->save($data);

        session()->setFlashdata('pesan', 'Data telah diinput');
        return redirect()->to(base_url('caleg'));
    }



    // public function createCalegKota()
    // {
    //     $no_urut = $this->request->getVar('no_urut');
    //     $nama = $this->request->getVar('nama');
    //     $cek_caleg = $this->calegkota
    //         ->where("no_urutkota", $no_urut)->countAllResults();
    //     if ($cek_caleg > 0) {
    //         session()->setFlashdata('gagal', 'Nomor Urut Telah Digunakan');
    //         return redirect()->to(base_url("caleg"));
    //     } else {
    //         $data = [
    //             "no_urutkota"    => esc($no_urut),
    //             "nama_calegkota" => esc($nama),
    //         ];
    //         $this->calegkota->save($data);
    //         session()->setFlashdata('pesan', 'data telah diinput');
    //         return redirect()->to(base_url("caleg"));
    //     }
    // }
    public function editCalegKota()
    {
        $id = $this->request->getVar("id");
        $no_urut  = $this->request->getVar("no_urut");
        $nama = $this->request->getVar("nama");
        //validation
        if (!$this->validate([
            'image' => [
                'rules' => 'max_size[image,2048]|is_image[image]|mime_in[image,image/jpeg,image/jpg,image/png]',
                'errors' => [
                    'max_size' => 'Ukuran file maks 2 MB',
                    'is_image' => 'File bukan gambar',
                    'mime_in' => 'File harus jpeg/jpg/png'
                ]
            ]
        ])) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->to(base_url("caleg"))->withInput();
        }

        $image = $this->request->getFile('image');

        // jika file tidak diupdate
        if ($image->getError() == 4) {
            $namaFile = $this->request->getVar('imageLama');
        } else {
            // ambil ekstensi file
            $typeFile = $image->guessExtension();

            // buat nama file baru
            $namaFile = "{$no_urut}_{$nama}." . $typeFile;
            // hapus file lama jika ada
            $imageLama = $this->request->getVar('imageLama');
            if (!empty($imageLama)) {
                $path = FCPATH . 'assets/calegImg/' . $imageLama;
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            // simpan file baru
            $image->move('./assets/calegImg/', $namaFile);
        }

        $cek_caleg = $this->calegkota->where("no_urutkota", $no_urut)->countAllResults();
        $q_caleg = $this->calegkota->getCalegKota($id);
        $no_urut_lama = $q_caleg['no_urutkota'];
        if ($cek_caleg > 0 && $no_urut_lama != $no_urut) {
            session()->setFlashdata('gagal', 'Nomor Urut Telah Digunakan');
            return redirect()->to(base_url("caleg"));
        } else {
            $this->calegkota->save([
                "id_calegkota" => $id,
                "no_urutkota" => esc($no_urut),
                "nama_calegkota" => esc($nama),
                "image" => $namaFile
            ]);
            session()->setFlashdata('pesan', 'data telah diubah');
            return redirect()->to(base_url("caleg"));
        }
    }
    public function deleteCaleg($id)
    {
        $this->calegkota->where("id_calegkota", $id)->delete();
        session()->setFlashdata('pesan', 'data telah dihapus');
        return redirect()->to(base_url("caleg"));
    }
}
