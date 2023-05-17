<?php

namespace App\Controllers;

use App\Models\OrangModel;
use App\Models\ConfigModel;

class Login extends BaseController
{
    public function index()
    {
        return view('loginView');
    }

    public function process()
    {
        $orang = new OrangModel();
        $config = new ConfigModel();
        $nope = $this->request->getVar('nope');
        $password = $this->request->getVar('sandi');
        $dataUser = $orang->where(['nope' => $nope,])->where(['status' => 'aktif'])->first();
        if ($dataUser) {
            if (password_verify($password, $dataUser['sandi'])) {
                session()->set([
                    'nama' => $dataUser['nama'],
                    'id_kecamatan' => $dataUser['id_kecamatan'],
                    'id_kelurahan' => $dataUser['id_kelurahan'],
                    'id_orang' => $dataUser['id_orang'],
                    'level' => $dataUser['level'],
                    'logged_in' => TRUE,
                    'sesi_daftar' => $config->getConfig()['sesi_daftar'],
                    'sesi_vote' => $config->getConfig()['sesi_vote'],
                ]);
                return redirect()->to(base_url('admin'));
            } else {
                session()->setFlashdata('error', 'No.Telp / Password Salah');
                return redirect()->to(base_url('/'));
            }
        } else {
            session()->setFlashdata('error', 'No.Telp / Password Salah');
            return redirect()->to(base_url('/'));
        }
    }
    function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('/'));
    }
}
