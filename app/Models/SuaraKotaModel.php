<?php

namespace App\Models;

use CodeIgniter\Model;

class SuaraKotaModel extends Model
{
    protected $primaryKey       = 'id_suarakota';

    protected $table            = 'suarakota';
    protected $useTimestamps    =   false;
    protected $allowedFields    = ['id_calegkota', 'jumlah_suara', 'id_saksi'];

    public function getSuaraKota($id = false)
    {
        if ($id == false) {
            return $this->findAll();
        }
        return $this->where(['id_suarakota' => $id])->first();
    }
    public function getResultKota($id = false)
    {
        if ($id == false) {
            return
                $this
                ->join('calegkota', 'suarakota.id_calegkota=calegkota.id_calegkota');
        }
        return
            $this
            ->join('calegkota', 'calegkota.id_calegkota=suarakota.id_calegkota')->where('id_saksi', $id)->find();
    }
    public function getResultKotaByLevel($id_level, $id)
    {
        return
            $this
            ->join('calegkota', 'calegkota.id_calegkota=suarakota.id_calegkota')
            ->join('tbl_orang', 'suarakota.id_saksi=tbl_orang.id_orang')
            ->select("*, SUM(suarakota.jumlah_suara) as jumlah_suara")
            ->groupBy("calegkota.id_calegkota")
            ->where($id_level, $id)
            ->findAll();
    }
    
    public function getAllResultKota()
    {
        return
            $this
            ->join('calegkota', 'calegkota.id_calegkota=suarakota.id_calegkota')
            ->select("*, SUM(suarakota.jumlah_suara) as jumlah_suara")
            ->groupBy("calegkota.id_calegkota")
            ->findAll();
    }
}
