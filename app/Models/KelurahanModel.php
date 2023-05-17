<?php

namespace App\Models;

use CodeIgniter\Model;

class KelurahanModel extends Model
{
    protected $primaryKey       = 'id_kelurahan';

    protected $table            = 'tbl_kelurahan';
    protected $useTimestamps    =   false;
    protected $allowedFields    = ['nama_kelurahan', 'id_kecamatan'];

    public function getKelurahan($id = false)
    {
        if ($id == false) {
            return $this->findAll();
        }
        return $this->where(['id_kelurahan' => $id])->first();
    }
}
