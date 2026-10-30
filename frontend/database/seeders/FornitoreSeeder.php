<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FornitoreSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('fornitori')->insert([
            ['nome' => 'Hotel Grandi Colline', 'ambito' => 'Alloggi', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Autonoleggio Express', 'ambito' => 'Mezzi', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Guide Alpine Italiane', 'ambito' => 'Servizi', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'B&B Vista Mare', 'ambito' => 'Alloggi', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Trenitalia / Transfer', 'ambito' => 'Mezzi', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}