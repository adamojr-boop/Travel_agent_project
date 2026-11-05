<?php

namespace App\Support;

use App\Models\Itinerario;
use App\Models\Regione;

class RegioniItinerario
{
    /** Città/località presenti negli itinerari d'esempio → regione. */
    private const CITTA = [
        'rome' => 'Lazio', 'roma' => 'Lazio',
        'milan' => 'Lombardia', 'milano' => 'Lombardia',
        'sandigliano' => 'Piemonte', 'alba' => 'Piemonte', 'oleggio castello' => 'Piemonte',
        'venice' => 'Veneto', 'venezia' => 'Veneto', 'san pietro in cariano' => 'Veneto',
        'florence' => 'Toscana', 'firenze' => 'Toscana', 'pisa' => 'Toscana',
        "colle di val d'elsa" => 'Toscana', 'radda in chianti' => 'Toscana',
        'cervinia' => "Valle d'Aosta",
        'sorrento' => 'Campania',
    ];

    public static function regioneDiCitta(?string $citta): ?string
    {
        return $citta ? (self::CITTA[mb_strtolower(trim($citta))] ?? null) : null;
    }

    /** Collega l'itinerario alle regioni delle città dei suoi giorni (anche più di una). */
    public static function sincronizza(Itinerario $itinerario): void
    {
        $ids = $itinerario->giorni()->pluck('citta')
            ->map(fn ($c) => self::regioneDiCitta($c))
            ->filter()->unique()
            ->map(fn ($n) => Regione::firstOrCreate(['nome' => $n])->id);

        $itinerario->regioni()->sync($ids);
    }
}
