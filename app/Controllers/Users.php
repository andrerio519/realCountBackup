<?php

namespace App\Controllers;

use App\Models\OrangModel;
use App\Models\KecamatanModel;
use App\Models\KelurahanModel;

class Users extends BaseController
{
    protected $orang;
    protected $kecamatan;
    protected $kelurahan;
    public function __construct()
    {
        $this->orang = new OrangModel();
        $this->kecamatan = new KecamatanModel();
        $this->kelurahan = new KelurahanModel();
    }
    public function index()
    {
        if (session()->get('level') == "saksi") {
            return redirect()->to(base_url('admin'));
        }
        $getlevel = session()->get('level');
        $getadmin = null;

        if ($getlevel == 'master admin') {
            $getadmin   = $this->orang->getUsersKecamatan()
                ->where("level", "admin_kecamatan")
                ->findAll();
            $getwilayah = $this->kecamatan->getKecamatan();
            $view       = "admin/adminKotaView";
        } else if ($getlevel == 'admin_kecamatan') {
            $getadmin = $this->orang->getUsersKelurahan()
                ->where("tbl_orang.id_kecamatan", session()->get('id_kecamatan'))
                ->findAll();
            $getwilayah = $this->kelurahan->where("id_kecamatan", session()->get('id_kecamatan'))->findAll();
            $view = "admin/adminKecamatanView";
        }
        if ($getlevel) {
            $data = [
                "title" => "Daftar Admin",
                "users" => $getadmin,
                "wilayah" => $getwilayah
            ];
            return view($view, $data);
        }
    }
    public function getUser()
    {
        $id = $this->request->getPost('id'); // Ambil data id dari input post
        $user = $this->orang->getOrg($id); // Dapatkan data user dari model

        echo json_encode($user);
    }
    public function userEdit()
    {
        $id = $this->request->getVar("id");
        $nama = $this->request->getVar("nama");
        $nope = $this->request->getVar("nope");
        $q_orang = $this->orang->getOrg($id);
        $cek_nope = $this->orang->where("nope", $nope)->countAllResults();
        $nope_lama = $q_orang["nope"];
        if ($cek_nope > 0 && $nope != $nope_lama) {
            session()->setFlashdata('gagal', 'Nomor Telepon sudah digunakan');
            return redirect()->to(base_url('users'));
        } else {
            $this->orang->save([
                "id_orang" => $id,
                "nama" => esc($nama),
                "nope" => esc($nope)
            ]);
            session()->setFlashdata('pesan', 'Data Telah Diubah');
            return redirect()->to(base_url('users'));
        }
    }
    public function deleteUser($id)
    {
        $this->orang->where("id_orang", $id)->delete();
        session()->setFlashdata('pesan', 'Data Telah Dihapus');
        return redirect()->to(base_url('users'));
    }
}
