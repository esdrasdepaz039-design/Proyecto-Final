<?php

namespace App\Controllers;

class Fines extends BaseController
{
    public function index()
    {
        return view('fines/index');
    }
}