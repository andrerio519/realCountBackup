<?php

namespace App\Models;

use CodeIgniter\Model;

class OrangModel extends Model
{
    protected $primaryKey       = 'id_orang';

    protected $table            = 'tbl_orang';
    protected $useTimestamps    =   false;
    protected $allowedFields    = ['id_org', 'nama', 'sandi', 'nope', 'id_kecamatan', 'id_kelurahan', 'tps', 'rt', 'level', 'add_by', 'status'];

    public function getOrg($id = false)
    {
        if ($id == false) {
            return $this;
        }
        return $this->where(['id_orang' => $id])->first();
    }
    public function getOrgbyIdKecamatan($id_kecamatan, $level)
    {
        return $this->where(['id_kecamatan' => $id_kecamatan])->where(["level" => $level])->first();
    }
    public function getOrgbyIdKelurahan($id_kelurahan, $level)
    {
        return $this->where(['id_kelurahan' => $id_kelurahan])->where(["level" => $level])->first();
    }
    public function getOrgbyAddBy($addBy)
    {
        return $this->where(['id_orang' => $addBy])->first();
    }
    public function getTps($id = false)
    {
        if ($id == false) {
            return $this
                ->join("tbl_tps.id_saksi=tbl_orang.id_orang")
                ->findAll();
        }
        return $this->join("tbl_tps", "tbl_tps.id_saksi=tbl_orang.id_orang")->where(['id_saksi' => $id])->first();
    }
    public function getAllTpsData()
    {
        return $this->join("tbl_tps", "tbl_tps.id_saksi=tbl_orang.id_orang")
            ->join("tbl_kecamatan", "tbl_orang.id_kecamatan=tbl_kecamatan.id_kecamatan")
            ->join("tbl_kelurahan", "tbl_orang.id_kelurahan=tbl_kelurahan.id_kelurahan")
            ->where(['level' => 'saksi'])->findAll();
    }


    public function getPartaiKotaTps($id = false)
    {
        if ($id == false) {
            return $this
                ->join("suarapartaikota.id_saksi=tbl_orang.id_orang")
                ->findAll();
        }
        return $this->join("suarapartaikota", "suarapartaikota.id_saksi=tbl_orang.id_orang")->where(['id_saksi' => $id])->first();
    }

    public function getUsersKecamatan()
    {
        return $this->join("tbl_kecamatan", "tbl_orang.id_kecamatan=tbl_kecamatan.id_kecamatan")
            ->where("tbl_orang.level", "admin_kecamatan");
    }
    public function getUsersKelurahan()
    {
        return $this->join("tbl_kelurahan", "tbl_orang.id_kelurahan=tbl_kelurahan.id_kelurahan")
            ->where("tbl_orang.level", "admin_kelurahan");
    }
}
