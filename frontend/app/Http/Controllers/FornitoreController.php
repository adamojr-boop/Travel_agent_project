<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FornitoreController extends Controller
{
    public function index()
    {
        return view('fornitori.index');
    }
}
