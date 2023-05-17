<?php

namespace App\Controllers;


use App\Models\KecamatanModel;
use App\Models\KelurahanModel;
use App\Models\TPSModel;
use App\Models\OrangModel;
use App\Models\SuaraKotaModel;
use App\Models\SuaraPartaiKotaModel;

class Kecamatan extends BaseController
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
        $this->orang    = new OrangModel;
        $this->suarakota = new SuaraKotaModel();
        $this->suarapartaikota = new SuaraPartaiKotaModel();
    }
    public function detail($id)
    {
        if (session()->get('level') == "saksi") {
            return redirect()->to(base_url('saksi'));
        }
        $kecamatan = $this->kecamatan->getKecamatan($id);
        if ($kecamatan == null) {
            return redirect()->to(base_url('admin'));
        }

        if (session()->get('level') == "admin_kecamatan") {
            if (session()->get('id_kecamatan') != $id) {
                return redirect()->to(base_url('admin'));
            }
        }
        $data = [
            "title"         => "Kecamatan",
            "kecamatan"     => $kecamatan,
            "kelurahan"     => $this->kelurahan->where("id_kecamatan", $id)->findAll(),
            "orang"         => $this->orang,
            "kota"          => $this->suarakota->getResultKotaByLevel("id_kecamatan", $id),
            "partaikota"    => $this->suarapartaikota->getResultPartaikotaByLevel("id_kecamatan", $id)
        ];
        return view('admin/kecamatanDetailView', $data);
    }
    public function getKelurahan()
    {
        $id = $this->request->getVar("id");
        $kelurahan = $this->kelurahan->getKelurahan($id);
        echo json_encode($kelurahan);
    }
    public function kelurahanCreate($id)
    {
        $nama_kelurahan     = $this->request->getVar('nama_kelurahan');
        $cek_kelurahan      = $this->kelurahan->where("nama_kelurahan", $nama_kelurahan)->countAllResults();
        if ($cek_kelurahan > 0) {
            session()->setFlashdata('gagal', 'Nama Kelurahan Telah Digunakan');
            return redirect()->to(base_url("kecamatan/detail/$id"));
        } else {
            $this->kelurahan->save([
                "nama_kelurahan" => esc($nama_kelurahan),
                "id_kecamatan"   => $id,
            ]);

            session()->setFlashdata('pesan', 'kelurahan telah ditambah');
            return redirect()->to(base_url("kecamatan/detail/$id"));
        }
    }
    public function kelurahanEdit()
    {
        $id = $this->request->getVar("id");
        $id_kecamatan = $this->request->getVar("id_kecamatan");
        $nama_kelurahan = $this->request->getVar("nama_kelurahan");
        $nama_kelurahan_lama = $this->kelurahan->getKelurahan($id)['nama_kelurahan'];
        $cek_kelurahan = $this->kelurahan->where("nama_kelurahan", $nama_kelurahan)->countAllResults();
        if ($cek_kelurahan > 0 && $nama_kelurahan_lama != $nama_kelurahan) {
            session()->setFlashdata('gagal', 'Nama Kelurahan Telah Digunakan');
            return redirect()->to(base_url("kecamatan/detail/$id_kecamatan"));
        } else {
            $this->kelurahan->save([
                "id_kelurahan" => $id,
                "nama_kelurahan" => esc($nama_kelurahan)
            ]);

            session()->setFlashdata('pesan', 'kelurahan telah diubah');
            return redirect()->to(base_url("kecamatan/detail/$id_kecamatan"));
        }
    }
    public function adminKelurahan($id)
    {
        $nama           = $this->request->getVar('nama');
        $sandi          = password_hash('paklurah', PASSWORD_BCRYPT);
        $nope           = $this->request->getVar('nope');
        $id_kelurahan   = $this->request->getVar('id_kelurahan');

        $this->orang->save([
            "nama"          => esc($nama),
            "sandi"         => $sandi,
            "nope"          => esc($nope),
            "id_kecamatan"  => $id,
            "id_kelurahan"  => esc($id_kelurahan),
            "level"         => 'admin_kelurahan',
            "add_by"        => session()->get('id_orang'),
            "status"        => 'aktif',
        ]);
        session()->setFlashdata('pesan', 'admin kelurahan telah ditambah');
        return redirect()->to(base_url("users"));
    }
    public function deleteKelurahan($id)
    {

        $this->kelurahan->delete($id);

        if ($this->tps->join("tbl_orang", "tbl_tps.id_saksi=tbl_orang.id_orang")->where("tbl_orang.id_kelurahan", $id)->countAllResults() > 0) {
            $query = "DELETE tbl_tps FROM tbl_tps JOIN tbl_orang ON tbl_tps.id_saksi = tbl_orang.id_orang WHERE tbl_orang.id_kelurahan = ?";
            $this->tps->query($query, [$id]);
        }



        if ($this->suarakota->join("tbl_orang", "suarakota.id_saksi=tbl_orang.id_orang")->where("tbl_orang.id_kelurahan", $id)->countAllResults() > 0) {
            $query = "DELETE suarakota FROM suarakota JOIN tbl_orang ON suarakota.id_saksi = tbl_orang.id_orang WHERE tbl_orang.id_kelurahan = ?";
            $this->suarakota->query($query, [$id]);
        }

        if ($this->suarapartaikota->join("tbl_orang", "suarapartaikota.id_saksi=tbl_orang.id_orang")->where("tbl_orang.id_kelurahan", $id)->countAllResults() > 0) {
            $query = "DELETE suarapartaikota FROM suarapartaikota JOIN tbl_orang ON suarapartaikota.id_saksi = tbl_orang.id_orang WHERE tbl_orang.id_kelurahan = ?";
            $this->suarapartaikota->query($query, [$id]);
        }
        if ($this->orang->where("id_kelurahan", $id)->countAllResults() > 0) {
            $this->orang->where("id_kelurahan", $id)->delete();
        }

        // Set pesan flashdata dan redirect ke halaman utama
        session()->setFlashdata('pesan', 'Kelurahan telah dihapus');
        return redirect()->back();
    }
}
