<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Regione extends Model
{
    protected $table = 'regioni';

    protected $fillable = ['nome'];

    public function itinerari(): BelongsToMany
    {
        return $this->belongsToMany(Itinerario::class, 'itinerario_regione', 'regione_id', 'itinerario_id');
    }
}