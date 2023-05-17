<?php

namespace App\Controllers;

class Admin extends BaseController
{
    public function index()
    {
        if (session()->get('level') == "saksi") {
            return redirect()->to(base_url('saksi'));
        }
        $data = ["title" => "Dashboard"];
        return view('admin/index', $data);
    }
}
