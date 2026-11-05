<?php

namespace Database\Seeders;

use App\Models\Fornitore;
use App\Models\Regione;
use App\Support\RegioniItinerario;
use Illuminate\Database\Seeder;

class FornitoreSeeder extends Seeder
{
    /** Hotel reali estratti dai PDF d'esempio (database/seeders/data/esempi.json). */
    public function run(): void
    {
        $dati = json_decode(file_get_contents(database_path('seeders/data/esempi.json')), true);

        foreach ($dati['fornitori'] as $f) {
            $regione = RegioniItinerario::regioneDiCitta($f['citta'] ?? null);
            $f['regione_id'] = $regione ? Regione::firstOrCreate(['nome' => $regione])->id : null;

            Fornitore::updateOrCreate(['nome' => $f['nome']], $f);
        }
    }
}
