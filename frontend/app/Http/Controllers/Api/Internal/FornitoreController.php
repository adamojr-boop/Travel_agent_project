<?php

namespace App\Http\Controllers\Api\Internal;

use App\Http\Controllers\Controller;
use App\Models\Fornitore;
use Illuminate\Http\Request;

class FornitoreController extends Controller
{
    public function index(Request $request)
    {
        $query = Fornitore::query();

        // Filtro facoltativo per ambito (Alloggi, Mezzi, Servizi)
        if ($request->filled('ambito')) {
            $query->where('ambito', 'like', '%' . $request->ambito . '%');
        }

        // Limitiamo i risultati per non sovraccaricare il contesto dell'IA
        $fornitori = $query->limit(20)->get(['id', 'nome', 'ambito']);

        return response()->json($fornitori);
    }
}