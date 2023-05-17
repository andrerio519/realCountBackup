<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfigModel extends Model
{
    protected $primaryKey       = 'id_config';

    protected $table            = 'tbl_config';
    protected $useTimestamps    =   false;
    protected $allowedFields    = ['nama_aplikasi', 'no_urut_partai', 'nama_partai', 'logo', 'sesi_daftar', 'sesi_vote'];

    public function getConfig()
    {
        return $this->where("id_config", 1)->first();
    }
}
