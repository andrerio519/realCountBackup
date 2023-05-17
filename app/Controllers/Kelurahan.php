<?php

namespace App\Controllers;

use App\Models\KecamatanModel;
use App\Models\KelurahanModel;
use App\Models\TPSModel;
use App\Models\SuaraPartaiKotaModel;
use App\Models\SuaraKotaModel;
use App\Models\OrangModel;

class Kelurahan extends BaseController
{

    protected $kecamatan;
    protected $kelurahan;
    protected $tps;
    protected $suarapartaikota;
    protected $suarakota;
    protected $orang;
    public function __construct()
    {
        $this->kecamatan = new KecamatanModel();
        $this->kelurahan = new KelurahanModel();
        $this->tps = new TPSModel();
        $this->suarapartaikota = new SuaraPartaiKotaModel();
        $this->suarakota = new SuaraKotaModel();
        $this->orang    = new OrangModel;
    }
    public function detail($id)
    {
        if (session()->get('level') == "saksi") {
            return redirect()->to(base_url('saksi'));
        }

        $kelurahan = $this->kelurahan->getKelurahan($id);
        if ($kelurahan == null) {
            return redirect()->to(base_url('admin'));
        }

        if (session()->get('level') == "admin_kecamatan") {
            if (session()->get('id_kecamatan') != $kelurahan['id_kecamatan']) {
                return redirect()->to(base_url('admin'));
            }
        }
        if (session()->get('level') == "admin_kelurahan") {
            if (session()->get('id_kelurahan') != $id) {
                return redirect()->to(base_url('admin'));
            }
        }
        // else {
        //     return redirect()->to(base_url('admin'));
        // }

        $data = [
            "title"         => "Kelurahan",
            "kelurahan"     => $kelurahan,
            "orang"         => $this->orang->where("id_kelurahan", $id)->where("level", "saksi")->findAll(),
            "add_by"        => $this->orang,
            "kota"          => $this->suarakota->getResultKotaByLevel("id_kelurahan", $id),
            "partaikota"    => $this->suarapartaikota->getResultPartaikotaByLevel("id_kelurahan", $id)
        ];

        return view('admin/kelurahanDetailView', $data);
    }

    public function saksiCreate($id)
    {
        $query_kelurahan = $this->kelurahan->getKelurahan($id);
        $id_kecamatan    = $query_kelurahan['id_kecamatan'];
        $nama            = $this->request->getVar('nama');
        $sandi           = password_hash('jonpantau', PASSWORD_BCRYPT);
        $nope            = $this->request->getVar('nope');
        $tps             = $this->request->getVar('tps');
        $rt              = $this->request->getVar('rt');
        $cek_nope = $this->orang->where("nope", $nope)->countAllResults();
        $cek_tps = $this->orang->where("id_kelurahan", $id)->where("tps", $tps)->countAllResults();
        $cek_rt = $this->orang->where("id_kelurahan", $id)->where("rt", $rt)->countAllResults();
        if ($cek_nope > 0) {
            session()->setFlashdata('gagal', 'Nomor Telepon Sudah digunakan');
            return redirect()->to(base_url("kelurahan/detail/$id"));
        } elseif ($cek_tps > 0) {
            session()->setFlashdata('gagal', 'Nomor TPS Sudah terdaftar');
            return redirect()->to(base_url("kelurahan/detail/$id"));
        } elseif ($cek_rt > 0) {
            session()->setFlashdata('gagal', 'RT Sudah terdaftar');
            return redirect()->to(base_url("kelurahan/detail/$id"));
        } else {
            $this->orang->save([
                "nama"          => esc($nama),
                "sandi"         => $sandi,
                "nope"          => esc($nope),
                "id_kecamatan"  => $id_kecamatan,
                "id_kelurahan"  => $id,
                "tps"           => esc($tps),
                "rt"            => esc($rt),
                "level"         => 'saksi',
                "add_by"        => session()->get('id_orang'),
                "status"        => 'aktif',
            ]);

            $this->tps->save([
                "id_saksi" => $this->orang->getInsertID(),
            ]);
            $this->suarapartaikota->save([
                "id_saksi" => $this->orang->getInsertID(),
            ]);

            session()->setFlashdata('pesan', 'saksi telah ditambah');
            return redirect()->to(base_url("kelurahan/detail/$id"));
        }
    }
    public function saksiEdit()
    {
        $id = $this->request->getVar("id");
        $id_kelurahan = $this->request->getVar("id_kelurahan");
        $nama = $this->request->getVar("nama");
        $nope = $this->request->getVar("nope");
        $tps = $this->request->getVar("tps");
        $rt = $this->request->getVar("rt");
        $q_orang = $this->orang->getOrg($id);
        $cek_nope = $this->orang->where("nope", $nope)->countAllResults();
        $cek_tps = $this->orang->where("id_kelurahan", $id_kelurahan)->where("tps", $tps)->countAllResults();
        $cek_rt = $this->orang->where("id_kelurahan", $id_kelurahan)->where("rt", $rt)->countAllResults();
        $nope_lama = $q_orang["nope"];
        $tps_lama = $q_orang["tps"];
        $rt_lama = $q_orang["rt"];
        if ($cek_nope > 0 && $nope != $nope_lama) {
            session()->setFlashdata('gagal', 'Nomor Telepon sudah digunakan');
            return redirect()->to(base_url("kelurahan/detail/$id_kelurahan"));
        }
        if ($cek_tps > 0 && $tps != $tps_lama) {
            session()->setFlashdata('gagal', 'Nomor TPS Sudah terdaftar');
            return redirect()->to(base_url("kelurahan/detail/$id_kelurahan"));
        }
        if ($cek_rt > 0 && $rt != $rt_lama) {
            session()->setFlashdata('gagal', 'RT Sudah terdaftar');
            return redirect()->to(base_url("kelurahan/detail/$id_kelurahan"));
        }
        $this->orang->save([
            "id_orang" => $id,
            "nama" => esc($nama),
            "nope" => esc($nope),
            "tps" => esc($tps),
            "rt" => esc($rt),
        ]);
        session()->setFlashdata('pesan', 'Data Telah Diubah');
        return redirect()->to(base_url("kelurahan/detail/$id_kelurahan"));
    }
    public function deleteTPS($id)
    {

        if ($this->tps->join("tbl_orang", "tbl_tps.id_saksi=tbl_orang.id_orang")->where("tbl_orang.id_orang", $id)->countAllResults() > 0) {
            $query = "DELETE tbl_tps FROM tbl_tps JOIN tbl_orang ON tbl_tps.id_saksi = tbl_orang.id_orang WHERE tbl_orang.id_orang = ?";
            $this->tps->query($query, [$id]);
        }
        if ($this->suarakota->join("tbl_orang", "suarakota.id_saksi=tbl_orang.id_orang")->where("tbl_orang.id_orang", $id)->countAllResults() > 0) {
            $query = "DELETE suarakota FROM suarakota JOIN tbl_orang ON suarakota.id_saksi = tbl_orang.id_orang WHERE tbl_orang.id_orang = ?";
            $this->suarakota->query($query, [$id]);
        }

        if ($this->suarapartaikota->join("tbl_orang", "suarapartaikota.id_saksi=tbl_orang.id_orang")->where("tbl_orang.id_orang", $id)->countAllResults() > 0) {
            $query = "DELETE suarapartaikota FROM suarapartaikota JOIN tbl_orang ON suarapartaikota.id_saksi = tbl_orang.id_orang WHERE tbl_orang.id_orang = ?";
            $this->suarapartaikota->query($query, [$id]);
        }
        if ($this->orang->where("id_orang", $id)->countAllResults() > 0) {
            $this->orang->where("id_orang", $id)->delete();
        }

        // Set pesan flashdata dan redirect ke halaman utama
        session()->setFlashdata('pesan', 'TPS dan Saksi telah dihapus');
        return redirect()->back();
    }
}
