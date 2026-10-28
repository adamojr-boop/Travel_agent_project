<?php

namespace Database\Seeders;

use App\Models\Itinerario;
use App\Models\Regione;
use Illuminate\Database\Seeder;

class ItinerarioSeeder extends Seeder
{
    public function run(): void
    {
        $viaggi = [
            [
                'titolo' => 'Tour del Chianti e Borghi Toscani',
                'descrizione' => '4 giorni tra degustazioni di vino, colline senesi e borghi medievali.',
                'prezzo' => 450.00,
                'durata_giorni' => 4,
                'regioni' => ['Toscana', 'Umbria']
            ],
            [
                'titolo' => 'Meraviglie della Costiera Amalfitana',
                'descrizione' => 'Panorama mozzafiato tra Positano, Amalfi e Capri.',
                'prezzo' => 750.00,
                'durata_giorni' => 5,
                'regioni' => ['Campania']
            ],
            [
                'titolo' => 'Trekking tra le Dolomiti Trentine',
                'descrizione' => 'Aria pura, rifugi in quota e sentieri spettacolari.',
                'prezzo' => 390.00,
                'durata_giorni' => 3,
                'regioni' => ['Trentino-Alto Adige', 'Veneto']
            ],
            [
                'titolo' => 'Barocco Siciliano e Valle dei Templi',
                'descrizione' => 'Un viaggio tra storia millenaria, mare cristallino e cucina tipica.',
                'prezzo' => 890.00,
                'durata_giorni' => 7,
                'regioni' => ['Sicilia']
            ]
        ];

        foreach ($viaggi as $dati) {
            $nomiRegioni = $dati['regioni'];
            unset($dati['regioni']);

            $itinerario = Itinerario::create($dati);

            $regioni = Regione::whereIn('nome', $nomiRegioni)->get();
            $itinerario->regioni()->attach($regioni);
        }
    }
}