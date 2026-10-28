<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regioni = [
            ['nome' => 'Abruzzo'],
            ['nome' => 'Basilicata'],
            ['nome' => 'Calabria'],
            ['nome' => 'Campania'],
            ['nome' => 'Emilia-Romagna'],
            ['nome' => 'Friuli-Venezia Giulia'],
            ['nome' => 'Lazio'],
            ['nome' => 'Liguria'],
            ['nome' => 'Lombardia'],
            ['nome' => 'Marche'],
            ['nome' => 'Molise'],
            ['nome' => 'Piemonte'],
            ['nome' => 'Puglia'],
            ['nome' => 'Sardegna'],
            ['nome' => 'Sicilia'],
            ['nome' => 'Toscana'],
            ['nome' => 'Trentino-Alto Adige'],
            ['nome' => 'Umbria'],
            ['nome' => "Valle d'Aosta"],
            ['nome' => 'Veneto'],
        ];

        DB::table('regioni')->insert($regioni);
    }
}