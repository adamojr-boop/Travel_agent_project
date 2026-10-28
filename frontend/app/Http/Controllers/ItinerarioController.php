<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItinerarioController extends Controller
{
    public function index()
    {
        // Restituisce la vista che contiene il layout principale con la sidebar
        return view('itinerari.index'); 
    }
}