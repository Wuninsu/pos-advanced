<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PosController extends Controller
{
    public function pos()
    {
        return  view('pos', ['page_title' => 'POS']);
    }

    public function monitor()
    {
        return  view('monitor', ['page_title' => 'Stream Updates']);
    }
}
