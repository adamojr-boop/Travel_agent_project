<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use RuntimeException;

class InitialDataSeederGuard
{
    public static function ensureUnclaimed(): void
    {
        if (DB::table('initial_data_owner')->where('id', 1)->whereNotNull('user_id')->exists()) {
            throw new RuntimeException('I dati iniziali sono già stati assegnati: il seeder non può sovrascrivere dati privati.');
        }
    }
}
