<?php

namespace App\Models;

use CodeIgniter\Model;

class TPSModel extends Model
{
    protected $primaryKey       = 'id_tps';

    protected $table            = 'tbl_tps';
    protected $useTimestamps    =   false;
    protected $allowedFields    = ['id_saksi', 'dpt', 'image', 'updated_by', 'updated_at'];

    public function getTpsDataByLevel($level, $id)
    {
        return $this
            ->join("tbl_orang", "tbl_tps.id_saksi=tbl_orang.id_orang")
            ->where($level, $id)->where("level", "saksi");
    }
    public function getAllTpsData()
    {
        return $this
            ->join("tbl_orang", "tbl_tps.id_saksi=tbl_orang.id_orang")
            ->where("level", "saksi")->findAll();
    }
}
