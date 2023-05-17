<?php

namespace App\Models;

use CodeIgniter\Model;

class KecamatanModel extends Model
{
    protected $primaryKey       = 'id_kecamatan';

    protected $table            = 'tbl_kecamatan';
    protected $useTimestamps    =   false;
    protected $allowedFields    = ['nama_kecamatan'];

    public function getKecamatan($id = false)
    {
        if ($id == false) {
            return $this->findAll();
        }
        return $this->where(['id_kecamatan' => $id])->first();
    }
}
