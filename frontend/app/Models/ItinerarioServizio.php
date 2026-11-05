<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItinerarioServizio extends Model
{
    protected $table = 'itinerario_servizi';

    protected $fillable = [
        'giorno_id', 'fornitore_id', 'ordine', 'tipo', 'titolo', 'opzione', 'opzionale',
        'descrizione', 'partecipanti', 'durata', 'notti', 'inclusioni', 'esclusioni',
        'prezzo_a_persona', 'valuta',
    ];

    protected $casts = [
        'opzionale' => 'boolean',
        'inclusioni' => 'array',
        'esclusioni' => 'array',
        'prezzo_a_persona' => 'decimal:2',
    ];

    public function giorno(): BelongsTo
    {
        return $this->belongsTo(ItinerarioGiorno::class, 'giorno_id');
    }

    public function fornitore(): BelongsTo
    {
        return $this->belongsTo(Fornitore::class);
    }
}
