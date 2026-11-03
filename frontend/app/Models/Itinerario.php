<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Itinerario extends Model
{
    protected $table = 'itinerari';

    // Aggiunto 'type' tra i campi mass-assignable
    protected $fillable = ['type', 'titolo', 'descrizione', 'prezzo', 'durata_giorni'];

    // --- SCOPES DI FILTRAGGIO ---

    // Filtra per tipo ('wedding' o 'trip')
    public function scopeDiTipo(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

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

    public function fornitori(): BelongsToMany
    {
        return $query = $this->belongsToMany(Fornitore::class, 'itinerario_fornitore');
    }

    public function voci(): HasMany
    {
        return $this->hasMany(ItinerarioVoce::class);
    }
}