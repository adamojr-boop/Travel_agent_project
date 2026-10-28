<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegioneController extends Controller
{
    public function index()
    {
        return view('regioni.index');
    }
}
