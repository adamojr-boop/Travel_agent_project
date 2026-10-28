<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Itinerario extends Model
{
    protected $table = 'itinerari';

    protected $fillable = ['titolo', 'descrizione', 'prezzo', 'durata_giorni'];

    public function regioni(): BelongsToMany
    {
        return $this->belongsToMany(Regione::class, 'itinerario_regione', 'itinerario_id', 'regione_id');
    }

    // --- SCOPES DI FILTRAGGIO ---

    // Filtra per prezzo massimo
    public function scopePrezzoMax(Builder $query, $prezzo): Builder
    {
        return $query->where('prezzo', '<=', $prezzo);
    }

    // Filtra per durata minima in giorni
    public function scopeDurataMinima(Builder $query, $giorni): Builder
    {
        return $query->where('durata_giorni', '>=', $giorni);
    }

    // Filtra per nome della regione collegata
    public function scopeInRegione(Builder $query, string $nomeRegione): Builder
    {
        return $query->whereHas('regioni', function ($q) use ($nomeRegione) {
            $q->where('nome', $nomeRegione);
        });
    }
}