<?php

namespace App\Livewire;

use App\Models\Itinerario;
use App\Models\Regione;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layout')] // <-- Questo dice a Livewire di usare <x-layout>
class ItinerariIndex extends Component
{
    public $searchRegione = '';
    public $prezzoMax = '';
    public $durataMinima = '';

    public function render()
    {
        $query = Itinerario::with('regioni');

        if (!empty($this->searchRegione)) {
            $query->inRegione($this->searchRegione);
        }

        if (!empty($this->prezzoMax)) {
            $query->prezzoMax($this->prezzoMax);
        }

        if (!empty($this->durataMinima)) {
            $query->durataMinima($this->durataMinima);
        }

        return view('livewire.itinerari-index', [
            'itinerari' => $query->get(),
            'regioni' => Regione::orderBy('nome')->get()
        ]);
    }
}