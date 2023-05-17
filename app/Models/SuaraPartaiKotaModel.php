<?php

namespace App\Models;

use CodeIgniter\Model;

class SuaraPartaiKotaModel extends Model
{
    protected $primaryKey       = 'id_suarapartaikota';

    protected $table            = 'suarapartaikota';
    protected $useTimestamps    =   false;
    protected $allowedFields    = ['suara_partaikota', 'id_saksi'];

    public function getTpsPartaiKota($id = false)
    {
        if ($id == false) {
            return $this->findAll();
        }
        return $this->where(['id_saksi' => $id])->first();
    }
    public function getResultPartaiKota()
    {
        $query = $this->selectSum('suara_partaikota')->get();
        return $query->getRow();
    }


    public function getResultPartaikotaByLevel($id_level, $id)
    {
        return $this->selectSum('suara_partaikota')
            ->join('tbl_orang', 'tbl_orang.id_orang = suarapartaikota.id_saksi')
            ->where($id_level, $id)
            ->get()
            ->getRowArray();
    }
}
