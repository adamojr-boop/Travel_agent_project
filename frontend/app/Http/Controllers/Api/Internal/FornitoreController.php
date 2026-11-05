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

        if ($request->filled('regione')) {
            $query->whereHas('regione', fn ($q) => $q->where('nome', 'like', '%' . $request->regione . '%'));
        }

        // Limitiamo i risultati per non sovraccaricare il contesto dell'IA
        $fornitori = $query->with('regione:id,nome')->limit(20)->get(['id', 'nome', 'ambito', 'citta', 'regione_id'])
            ->map(fn ($f) => [
                'id' => $f->id,
                'nome' => $f->nome,
                'ambito' => $f->ambito,
                'citta' => $f->citta,
                'regione' => $f->regione?->nome,
            ]);

        return response()->json($fornitori);
    }
}