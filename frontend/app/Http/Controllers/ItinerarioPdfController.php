<?php

namespace App\Http\Controllers;

use App\Models\Itinerario;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class ItinerarioPdfController extends Controller
{
    public function __invoke($id)
    {
        $itinerario = Itinerario::with(['giorni.servizi.fornitore'])->findOrFail($id);

        $nome = Str::slug($itinerario->codice ?: $itinerario->titolo) . '.pdf';

        return Pdf::loadView('itinerari.pdf', ['itinerario' => $itinerario])
            ->setPaper('a4')
            ->download($nome);
    }
}
