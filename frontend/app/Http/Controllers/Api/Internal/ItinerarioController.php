<?php

namespace App\Http\Controllers\Api\Internal;

use App\Http\Controllers\Controller;
use App\Models\Fornitore;
use App\Models\Itinerario;
use App\Support\RegioniItinerario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ItinerarioController extends Controller
{
    public const TIPI_SERVIZIO = [
        'Private Transfer', 'Private Tour with Vehicle', 'Private Guide Service',
        'Private Transportation Service', 'Group Guide Service', 'Accommodation', 'Assistance',
    ];

    public function index(Request $request)
    {
        $query = Itinerario::query()->with(['regioni:id,nome', 'fornitori:id,nome']);

        if ($request->filled('tipo')) {
            $query->diTipo($request->tipo);
        }
        if ($request->filled('regione')) {
            $query->inRegione($request->regione);
        }
        if ($request->filled('fornitore')) {
            $query->conFornitore($request->fornitore);
        }
        if ($request->filled('citta')) {
            $query->whereHas('giorni', fn ($q) => $q->where('citta', 'like', '%' . $request->citta . '%'));
        }
        if ($request->filled('durata_min')) {
            $query->durataMinima((int) $request->durata_min);
        }
        if ($request->filled('stato')) {
            $query->where('stato', $request->stato);
        }

        $itinerari = $query->latest('id')->limit(10)->get()->map(fn (Itinerario $i) => [
            'id' => $i->id,
            'codice' => $i->codice,
            'titolo' => $i->titolo,
            'tipo' => $i->type,
            'stato' => $i->stato,
            'data_inizio' => $i->data_inizio?->toDateString(),
            'data_fine' => $i->data_fine?->toDateString(),
            'notti' => $i->notti,
            'persone' => $i->persone,
            'regioni' => $i->regioni->pluck('nome'),
            'fornitori' => $i->fornitori->pluck('nome'),
        ]);

        return response()->json($itinerari);
    }

    public function show(Itinerario $itinerario)
    {
        $itinerario->load(['regioni:id,nome', 'giorni.servizi.fornitore:id,nome']);

        return response()->json([
            'id' => $itinerario->id,
            'codice' => $itinerario->codice,
            'titolo' => $itinerario->titolo,
            'tipo' => $itinerario->type,
            'stato' => $itinerario->stato,
            'data_inizio' => $itinerario->data_inizio?->toDateString(),
            'data_fine' => $itinerario->data_fine?->toDateString(),
            'notti' => $itinerario->notti,
            'persone' => $itinerario->persone,
            'valuta' => $itinerario->valuta,
            'regioni' => $itinerario->regioni->pluck('nome'),
            'giorni' => $itinerario->giorni->map(fn ($g) => [
                'numero' => $g->numero,
                'data' => $g->data?->toDateString(),
                'citta' => $g->citta,
                'intro' => $g->intro,
                'servizi' => $g->servizi->map(fn ($s) => array_filter([
                    'tipo' => $s->tipo,
                    'titolo' => $s->titolo,
                    'opzione' => $s->opzione,
                    'opzionale' => $s->opzionale ?: null,
                    'descrizione' => $s->descrizione,
                    'partecipanti' => $s->partecipanti,
                    'durata' => $s->durata,
                    'notti' => $s->notti,
                    'inclusioni' => $s->inclusioni,
                    'esclusioni' => $s->esclusioni,
                    'prezzo_a_persona' => $s->prezzo_a_persona,
                    'valuta' => $s->valuta,
                    'fornitore' => $s->fornitore?->nome,
                ], fn ($v) => $v !== null)),
            ]),
        ]);
    }

    /** Salva sempre come bozza: la conferma resta all'utente. */
    public function store(Request $request)
    {
        $dati = $request->validate([
            'titolo' => 'required|string|max:255',
            'type' => ['required', Rule::in(['trip', 'wedding'])],
            'data_inizio' => 'nullable|date',
            'data_fine' => 'nullable|date|after_or_equal:data_inizio',
            'persone' => 'nullable|integer|min:1',
            'valuta' => 'nullable|string|size:3',
            'giorni' => 'required|array|min:1',
            'giorni.*.numero' => 'required|integer|min:1',
            'giorni.*.data' => 'nullable|date',
            'giorni.*.citta' => 'nullable|string|max:255',
            'giorni.*.intro' => 'nullable|string',
            'giorni.*.servizi' => 'nullable|array',
            'giorni.*.servizi.*.tipo' => ['required', Rule::in(self::TIPI_SERVIZIO)],
            'giorni.*.servizi.*.titolo' => 'required|string|max:255',
            'giorni.*.servizi.*.opzione' => 'nullable|integer|min:1',
            'giorni.*.servizi.*.opzionale' => 'nullable|boolean',
            'giorni.*.servizi.*.descrizione' => 'nullable|string',
            'giorni.*.servizi.*.partecipanti' => 'nullable|integer|min:1',
            'giorni.*.servizi.*.durata' => 'nullable|string|max:50',
            'giorni.*.servizi.*.notti' => 'nullable|integer|min:1',
            'giorni.*.servizi.*.inclusioni' => 'nullable|array',
            'giorni.*.servizi.*.esclusioni' => 'nullable|array',
            'giorni.*.servizi.*.prezzo_a_persona' => 'nullable|numeric|min:0',
            'giorni.*.servizi.*.valuta' => 'nullable|string|size:3',
            'giorni.*.servizi.*.fornitore' => 'nullable|string|max:255',
        ]);

        $itinerario = DB::transaction(function () use ($dati) {
            $inizio = isset($dati['data_inizio']) ? \Carbon\Carbon::parse($dati['data_inizio']) : null;
            $fine = isset($dati['data_fine']) ? \Carbon\Carbon::parse($dati['data_fine']) : null;
            $numeroGiorni = count($dati['giorni']);

            $itinerario = Itinerario::create([
                'titolo' => $dati['titolo'],
                'type' => $dati['type'],
                'data_inizio' => $inizio,
                'data_fine' => $fine,
                'persone' => $dati['persone'] ?? null,
                'valuta' => strtoupper($dati['valuta'] ?? 'USD'),
                'durata_giorni' => $numeroGiorni,
                'notti' => max($numeroGiorni - 1, 0),
                'prezzo' => 0,
                'stato' => 'bozza',
            ]);

            foreach ($dati['giorni'] as $g) {
                $servizi = $g['servizi'] ?? [];
                unset($g['servizi']);
                $giorno = $itinerario->giorni()->create($g);

                foreach ($servizi as $i => $s) {
                    $nome = $s['fornitore'] ?? null;
                    unset($s['fornitore']);
                    $s['fornitore_id'] = $nome ? Fornitore::where('nome', 'like', '%' . $nome . '%')->value('id') : null;
                    $s['ordine'] = $i + 1;
                    $giorno->servizi()->create($s);
                }
            }

            $itinerario->fornitori()->sync(
                $itinerario->giorni()->with('servizi')->get()->flatMap->servizi->pluck('fornitore_id')->filter()->unique()
            );
            RegioniItinerario::sincronizza($itinerario);

            return $itinerario;
        });

        return response()->json(['id' => $itinerario->id, 'stato' => 'bozza', 'titolo' => $itinerario->titolo], 201);
    }
}
