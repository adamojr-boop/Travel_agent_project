<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItinerarioController extends Controller
{
    public function index()
    {
        // Qui richiamiamo la vista Blade degli itinerari che abbiamo fatto prima
        return view('itinerari.index');
    }
}