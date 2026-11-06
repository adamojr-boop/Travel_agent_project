<?php

namespace Database\Seeders;

use App\Models\Fornitore;
use App\Models\Itinerario;
use App\Support\RegioniItinerario;
use App\Support\InitialDataSeederGuard;
use Illuminate\Database\Seeder;

class ItinerarioSeeder extends Seeder
{
    /** Itinerari reali (nomi cliente anonimizzati) tratti dai PDF d'esempio. */
    public function run(): void
    {
        InitialDataSeederGuard::ensureUnclaimed();
        $dati = json_decode(file_get_contents(database_path('seeders/data/esempi.json')), true);
        $fornitori = Fornitore::pluck('id', 'nome');

        foreach ($dati['itinerari'] as $i) {
            $giorni = $i['giorni_dettaglio'];
            $durata = $i['giorni'];
            unset($i['giorni_dettaglio'], $i['giorni']);

            $itinerario = Itinerario::updateOrCreate(
                ['codice' => $i['codice']],
                $i + ['descrizione' => null, 'prezzo' => 0, 'durata_giorni' => $durata]
            );

            // Ricostruisce i giorni a ogni esecuzione per restare idempotente.
            $itinerario->giorni()->delete();

            foreach ($giorni as $g) {
                $servizi = $g['servizi'];
                unset($g['servizi']);
                $giorno = $itinerario->giorni()->create($g);

                foreach ($servizi as $s) {
                    $nomeFornitore = $s['fornitore'] ?? null;
                    unset($s['fornitore']);
                    $s['fornitore_id'] = $nomeFornitore ? ($fornitori[$nomeFornitore] ?? null) : null;
                    $giorno->servizi()->create($s);
                }
            }

            $itinerario->fornitori()->sync(
                $itinerario->giorni()->with('servizi')->get()->flatMap->servizi->pluck('fornitore_id')->filter()->unique()
            );
            RegioniItinerario::sincronizza($itinerario);
        }
    }
}
