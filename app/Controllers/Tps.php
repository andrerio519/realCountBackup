<?php

namespace App\Controllers;

use Config\Services;
use App\Models\OrangModel;
use App\Models\SuaraPartaiKotaModel;
use App\Models\CalegKotaModel;
use App\Models\SuaraKotaModel;
use App\Models\KelurahanModel;
use App\Models\TPSModel;

class Tps extends BaseController
{
    protected $suarapartaikota;
    protected $suarakota;
    protected $calegkota;
    protected $tps_orang;
    protected $kelurahan;
    protected $tps;
    public function __construct()
    {
        $this->calegkota = new CalegKotaModel();
        $this->suarapartaikota = new SuaraPartaiKotaModel();
        $this->suarakota = new SuaraKotaModel();
        $this->tps_orang = new OrangModel();
        $this->kelurahan = new KelurahanModel();
        $this->tps = new TPSModel();
    }
    public function detail($id)
    {
        if (session()->get('level') == "saksi") {
            return redirect()->to(base_url('saksi'));
        }
        $query_orang = $this->tps_orang->getTps($id);
        if ($query_orang == null) {
            return redirect()->to(base_url('admin'));
        }
        if (session()->get('level') == "admin_kecamatan") {
            if (session()->get('id_kecamatan') != $query_orang['id_kecamatan']) {
                return redirect()->to(base_url('admin'));
            }
        }

        if (session()->get('level') == "admin_kelurahan") {
            if (session()->get('id_kelurahan') != $query_orang['id_kelurahan']) {
                return redirect()->to(base_url('admin'));
            }
        }
        $id_kelurahan = $query_orang['id_kelurahan'];
        $data = [
            "title" => "Detail TPS",
            "tps" => $this->tps_orang->getTps($id),
            "kelurahan" => $this->kelurahan->getKelurahan($id_kelurahan),
            "suarapartaikota" => $this->suarapartaikota->getTpsPartaiKota($id),
            "kota" => $this->suarakota->getResultKotaByLevel("id_saksi", $id),
            "pesertakota" => $this->calegkota->findAll()



        ];

        return view('admin/TpsDetailView', $data);
    }

    public function insert($id_saksi)
    {
        // Memulai transaksii
        $db = db_connect();
        $db->transStart();
        try {
            // Ubah dengan ID saksi yang sesuai
            $dpt = $this->request->getVar("dpt");
            $suara_partaikota = $this->request->getVar("suara_partaikota");
            $currenttime = date('Y-m-d H:i:s');
            $image = $this->request->getFile('image');
            //dd($image);
            //validation
            if (!$this->validate([
                'image' => [
                    'rules' => 'max_size[image,5120]|is_image[image]|mime_in[image,image/jpeg,image/jpg,image/png]',
                    'errors' => [
                        'max_size' => 'Ukuran file maks 5 MB',
                        'is_image' => 'File bukan gambar',
                        'mime_in' => 'File harus jpeg/jpg/png'
                    ]
                ]
            ])) {
                session()->setFlashdata('error', $this->validator->listErrors());
                if (session()->get('level') == "saksi") {
                    return redirect()->to(base_url('saksi'));
                } else {
                    return redirect()->to(base_url("tps/detail/$id_saksi"));
                }
            }

            $no_tps = $this->request->getVar("no_tps");
            $nama_kelurahan = $this->request->getVar("nama_kelurahan");


            // jika file tidak diupdate
            if ($image->getError() == 4) {
                $namaFile = $this->request->getVar('imageLama');
            } else {
                // ambil ekstensi file
                $typeFile = $image->guessExtension();

                // buat nama file baru
                $namaFile = "{$no_tps}_{$nama_kelurahan}." . $typeFile;
                // hapus file lama jika ada
                $imageLama = $this->request->getVar('imageLama');
                if (!empty($imageLama)) {
                    $path = FCPATH . 'assets/tps/' . $imageLama;
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }
                // Simpan file baru dengan kompresi
                $destinationPath = './assets/tps/' . $namaFile;
                // Looping untuk mencoba kompresi dengan kualitas yang berkurang
                for ($quality = 100; $quality >= 10; $quality -= 10) {
                    Services::image()
                        ->withFile($image)
                        ->save($destinationPath, $quality);

                    // Memeriksa ukuran file yang sudah dikompresi
                    $compressedSize = filesize($destinationPath);

                    // Jika ukuran file sudah kurang dari 1MB, keluar dari loop
                    if ($compressedSize <= 1024 * 1024) {
                        break;
                    }
                }
            }

            $info_tps = $this->tps_orang->getTps($id_saksi);
            $this->tps->update($info_tps['id_tps'], [
                'dpt' => esc($dpt),
                "image" => $namaFile,
                'updated_by' => session()->get('id_orang'),
                'updated_at' => $currenttime
            ]);
            // Ubah dengan ID saksi yang sesuai
            $id_calegkota = $this->request->getPost('id_calegkota');
            $jumlah_suarakota = $this->request->getPost('jumlah_suarakota');
            if ($id_calegkota != null) {
                foreach ($id_calegkota as $indexkota => $idkota) {
                    $datakota = [
                        'id_saksi' => $id_saksi,
                        'id_calegkota' => $idkota,
                        'jumlah_suara' => esc($jumlah_suarakota[$indexkota]),
                    ];
                    $suarakota = $this->suarakota->where(['id_saksi' => $id_saksi, 'id_calegkota' => $idkota])->first();

                    if ($suarakota) {
                        $this->suarakota->update($suarakota['id_suarakota'], $datakota);
                    } else {
                        $this->suarakota->save($datakota);
                    }
                }
            }
            $info_partaikota = $this->tps_orang->getPartaikotaTps($id_saksi);
            $this->suarapartaikota->update($info_partaikota['id_suarapartaikota'], [
                'suara_partaikota' => esc($suara_partaikota)
            ]);
            $db->transCommit();
            session()->setFlashdata('pesan', 'data telah diinput');
            if (session()->get('level') == "saksi") {
                return redirect()->to(base_url('saksi'));
            } else {
                return redirect()->to(base_url("tps/detail/$id_saksi"));
            }
        } catch (\Exception $e) {
            $db->transRollback();
            session()->setFlashdata('error', $e->getMessage());

            if (session()->get('level') == "saksi") {
                return redirect()->to(base_url('saksi'));
            } else {
                return redirect()->to(base_url("tps/detail/$id_saksi"));
            }
        } finally {
            // Memastikan transaksi selesai dan mengakhiri koneksi database
            $db->transComplete();
            $db->close();
        }
    }


    public function saksi()
    {
        if (session()->get('level') != "saksi") {
            return redirect()->to(base_url('admin'));
        }
        $id = session()->get('id_orang');
        $query_orang = $this->tps_orang->getOrg($id);
        $id_kelurahan = $query_orang['id_kelurahan'];
        $data = [
            "title" => "Detail TPS",
            "tps" => $this->tps_orang->getTps($id),
            "kelurahan" => $this->kelurahan->getKelurahan($id_kelurahan),
            "suarapartaikota" => $this->suarapartaikota->getTpsPartaiKota($id),
            "kota" => $this->suarakota->getResultKotaByLevel("id_saksi", $id),
            "pesertakota" => $this->calegkota->findAll()
        ];
        return view('admin/saksiView', $data);
    }
    public function dataTPS()
    {
        $tpsmasuk = $this->tps->where("updated_by !=", 0)->countAllResults();
        $tpstotal = $this->tps->countAllResults();


        if (session()->get("level") == "admin_kecamatan") {
            $tpsmasuk = $this->tps->getTpsDataByLevel("id_kecamatan", session()->get("id_kecamatan"))->where("updated_by !=", 0)->countAllResults();
            $tpstotal = $this->tps->getTpsDataByLevel("id_kecamatan", session()->get("id_kecamatan"))->countAllResults();
        }
        if (session()->get("level") == "admin_kelurahan") {
            $tpsmasuk = $this->tps->getTpsDataByLevel("id_kelurahan", session()->get("id_kelurahan"))->where("updated_by !=", 0)->countAllResults();
            $tpstotal = $this->tps->getTpsDataByLevel("id_kelurahan", session()->get("id_kelurahan"))->countAllResults();
        }


        $persen = ($tpstotal > 0) ? number_format(($tpsmasuk / $tpstotal * 100), 2) : 0;
        $data = [
            'persen' => $persen,
            'tps_masuk' => $tpsmasuk,
            'tps_total' => $tpstotal,
        ];

        return json_encode($data);
    }
    public function data()
    {
        $data = [
            "title" => "Data Per TPS",
            "orang" => $this->tps_orang->getAllTpsData(),
            "pesertakota" => $this->calegkota->findAll(),
            "suaraKota" => $this->suarakota,
            "suaraPartai" => $this->suarapartaikota,
            "updatedBy" => $this->tps_orang
        ];
        return view("admin/dataView", $data);
    }
}
/*
 
   ▄████████ ▄██   ▄    ▄██████▄     ▄██████▄     ▄█    █▄     ▄██████▄     ▄████████     ███     
  ███    ███ ███   ██▄ ███    ███   ███    ███   ███    ███   ███    ███   ███    ███ ▀█████████▄ 
  ███    ███ ███▄▄▄███ ███    ███   ███    █▀    ███    ███   ███    ███   ███    █▀     ▀███▀▀██ 
 ▄███▄▄▄▄██▀ ▀▀▀▀▀▀███ ███    ███  ▄███         ▄███▄▄▄▄███▄▄ ███    ███   ███            ███   ▀ 
▀▀███▀▀▀▀▀   ▄██   ███ ███    ███ ▀▀███ ████▄  ▀▀███▀▀▀▀███▀  ███    ███ ▀███████████     ███     
▀███████████ ███   ███ ███    ███   ███    ███   ███    ███   ███    ███          ███     ███     
  ███    ███ ███   ███ ███    ███   ███    ███   ███    ███   ███    ███    ▄█    ███     ███     
  ███    ███  ▀█████▀   ▀██████▀    ████████▀    ███    █▀     ▀██████▀   ▄████████▀     ▄████▀   
  ███    ███                                                                                      
 
 */