<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fornitore extends Model
{
    public const AMBITI = [
        'Alloggi' => 'Hotel',
        'Mezzi' => 'Transfers',
        'Servizi' => 'Tours',
    ];

    protected $table = 'fornitori';

    protected $fillable = [
        'nome',
        'ambito',
        'citta',
        'descrizione',
        'regione_id',
        'user_id',
    ];

    public function getAmbitoEtichettaAttribute(): ?string
    {
        return self::AMBITI[$this->ambito] ?? $this->ambito;
    }

    public function regione()
    {
        return $this->belongsTo(Regione::class);
    }

    public function itinerari()
    {
        return $this->belongsToMany(Itinerario::class, 'itinerario_fornitore');
    }
}