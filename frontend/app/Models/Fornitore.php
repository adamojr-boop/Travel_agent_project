<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fornitore extends Model
{
    protected $table = 'fornitori';

    protected $fillable = [
        'nome',
        'ambito',
        'citta',
        'descrizione',
        'regione_id',
    ];

    public function regione()
    {
        return $this->belongsTo(Regione::class);
    }

    public function itinerari()
    {
        return $this->belongsToMany(Itinerario::class, 'itinerario_fornitore');
    }
}