<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItinerarioVoce extends Model
{
    use HasFactory;

    protected $table = 'itinerario_voci';

    protected $fillable = [
        'itinerario_id',
        'fornitore_id',
        'descrizione',
        'prezzo_a_persona',
        'quantita_persone',
        'subtotal',
    ];

    public function itinerario()
    {
        return $this->belongsTo(Itinerario::class);
    }

    public function fornitore()
    {
        return $this->belongsTo(Fornitore::class);
    }
}