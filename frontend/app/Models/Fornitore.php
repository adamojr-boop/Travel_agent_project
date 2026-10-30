<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fornitore extends Model
{
    protected $table = 'fornitori';

    protected $fillable = [
        'nome',
        'ambito',
    ];
}