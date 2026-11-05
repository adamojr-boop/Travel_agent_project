<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItinerarioGiorno extends Model
{
    protected $table = 'itinerario_giorni';

    protected $fillable = ['itinerario_id', 'numero', 'data', 'citta', 'intro'];

    protected $casts = ['data' => 'date'];

    public function itinerario(): BelongsTo
    {
        return $this->belongsTo(Itinerario::class);
    }

    public function servizi(): HasMany
    {
        return $this->hasMany(ItinerarioServizio::class, 'giorno_id')->orderBy('ordine');
    }
}
