<?php

namespace App\Models;

use CodeIgniter\Model;

class CalegKotaModel extends Model
{
    protected $primaryKey       = 'id_calegkota';

    protected $table            = 'calegkota';
    protected $useTimestamps    =   false;
    protected $allowedFields    = ['no_urutkota', 'nama_calegkota', 'image'];

    public function getCalegKota($id = false)
    {
        if ($id == false) {
            return $this->findAll();
        }
        return $this->where(['id_calegkota' => $id])->first();
    }
    public function calegKotaTps($id)
    {
        return $this
            ->join("suarakota", "calegkota.id_calegkota=suarakota.id_calegkota")
            ->where("id_saksi", $id);
    }
}
