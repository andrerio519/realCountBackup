<?php

namespace App\Controllers;

use App\Models\KecamatanModel;
use App\Models\KelurahanModel;
use App\Models\TPSModel;
use App\Models\OrangModel;
use App\Models\SuaraKotaModel;
use App\Models\SuaraPartaiKotaModel;



class Kota extends BaseController
{


    protected $kecamatan;
    protected $kelurahan;
    protected $tps;
    protected $orang;
    protected $suarakota;
    protected $suarapartaikota;
    public function __construct()
    {
        $this->kecamatan = new KecamatanModel();
        $this->kelurahan = new KelurahanModel();
        $this->tps = new TPSModel();
        $this->suarakota = new SuaraKotaModel();
        $this->suarapartaikota = new SuaraPartaiKotaModel();
        $this->orang    = new OrangModel;
    }
    public function index()
    {
        if (session()->get('level') != "master admin") {
            return redirect()->to(base_url('admin'));
        }
        if (session()->get('level') == "saksi") {
            return redirect()->to(base_url('saksi'));
        }
        $data = [
            "title"         => "Kecamatan",
            "kecamatan"     => $this->kecamatan->findAll(),
            "orang"         => $this->orang,
            "kota"          => $this->suarakota->getAllResultKota(),
            "partaikota"    => $this->suarapartaikota->getResultPartaiKota()
        ];
        return view('admin/kecamatanView', $data);
    }
    public function getKecamatan()
    {
        $id = $this->request->getVar("id");
        $kecamatan = $this->kecamatan->getKecamatan($id);
        echo json_encode($kecamatan);
    }
    public function kecamatanCreate()
    {
        $nama_kecamatan     = $this->request->getVar('nama_kecamatan');
        $cek_kecamatan      = $this->kecamatan->where("nama_kecamatan", $nama_kecamatan)->countAllResults();

        if ($cek_kecamatan > 0) {
            session()->setFlashdata('gagal', 'Nama Kecamatan Sudah Digunakan');
            return redirect()->to(base_url("kecamatan"));
        }

        $this->kecamatan->save([
            "nama_kecamatan" => esc($nama_kecamatan),
        ]);

        session()->setFlashdata('pesan', 'Kecamatan telah ditambah');
        return redirect()->to(base_url("kecamatan"));
    }
    public function kecamatanEdit()
    {
        $id                  = $this->request->getVar("id");
        $nama_kecamatan      = $this->request->getVar('nama_kecamatan');
        $nama_kecamatan_lama = $this->kecamatan->getKecamatan($id)["nama_kecamatan"];
        $cek_kecamatan       = $this->kecamatan->where("nama_kecamatan", $nama_kecamatan)->countAllResults();

        if ($cek_kecamatan > 0 && $nama_kecamatan_lama != $nama_kecamatan) {
            session()->setFlashdata('gagal', 'Nama Kecamatan Sudah Digunakan');
            return redirect()->to(base_url("kecamatan"));
        } else {
            $this->kecamatan->save([
                "id_kecamatan" => $id,
                "nama_kecamatan" => esc($nama_kecamatan)
            ]);

            session()->setFlashdata('pesan', 'Kecamatan telah diubah');
            return redirect()->to(base_url("kecamatan"));
        }
    }
    public function adminKecamatanCreate()
    {
        $nama           = $this->request->getVar('nama');
        $sandi          = password_hash('pakcamat', PASSWORD_BCRYPT);
        $nope           = $this->request->getVar('nope');
        $id_kecamatan   = $this->request->getVar('id_kecamatan');
        $cek_nope = $this->orang->where("nope", $nope)->countAllResults();

        if ($cek_nope > 0) {
            session()->setFlashdata('gagal', 'Nomor Telepon sudah digunakan');
            return redirect()->to(base_url('users'));
        } else {

            $this->orang->save([
                "nama"          => esc($nama),
                "sandi"         => esc($sandi),
                "nope"          => esc($nope),
                "id_kecamatan"  => esc($id_kecamatan),
                "level"         => esc('admin_kecamatan'),
                "add_by"        => session()->get("id_orang"),
                "status"        => 'aktif',
            ]);
            session()->setFlashdata('pesan', 'admin Kecamatan telah ditambah');
            return redirect()->to(base_url("users"));
        }
    }
    public function deleteKecamatan($id)
    {

        $this->kecamatan->delete($id);

        if ($this->kelurahan->where("id_kecamatan", $id)->countAllResults() > 0) {
            $this->kelurahan->where("id_kecamatan", $id)->delete();
        }

        if ($this->tps->join("tbl_orang", "tbl_tps.id_saksi=tbl_orang.id_orang")->where("tbl_orang.id_kecamatan", $id)->countAllResults() > 0) {
            $query = "DELETE tbl_tps FROM tbl_tps JOIN tbl_orang ON tbl_tps.id_saksi = tbl_orang.id_orang WHERE tbl_orang.id_kecamatan = ?";
            $this->tps->query($query, [$id]);
        }


        if ($this->suarakota->join("tbl_orang", "suarakota.id_saksi=tbl_orang.id_orang")->where("tbl_orang.id_kecamatan", $id)->countAllResults() > 0) {
            $query = "DELETE suarakota FROM suarakota JOIN tbl_orang ON suarakota.id_saksi = tbl_orang.id_orang WHERE tbl_orang.id_kecamatan = ?";
            $this->suarakota->query($query, [$id]);
        }

        if ($this->suarapartaikota->join("tbl_orang", "suarapartaikota.id_saksi=tbl_orang.id_orang")->where("tbl_orang.id_kecamatan", $id)->countAllResults() > 0) {
            $query = "DELETE suarapartaikota FROM suarapartaikota JOIN tbl_orang ON suarapartaikota.id_saksi = tbl_orang.id_orang WHERE tbl_orang.id_kecamatan = ?";
            $this->suarapartaikota->query($query, [$id]);
        }
        if ($this->orang->where("id_kecamatan", $id)->countAllResults() > 0) {
            $this->orang->where("id_kecamatan", $id)->delete();
        }

        // Set pesan flashdata dan redirect ke halaman utama
        session()->setFlashdata('pesan', 'telah dihapus');
        return redirect()->to(base_url("kecamatan"));
    }
}
