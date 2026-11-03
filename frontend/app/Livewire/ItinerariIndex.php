<?php

namespace App\Livewire;

use App\Models\Itinerario;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layout')] 
class ItinerariIndex extends Component
{
    public $selectedType = 'all'; // Filtro: 'all', 'wedding', 'trip'
    public $prezzoMax = '';
    public $durataMinima = '';

    // Metodo per cambiare il tab/filtro attivo
    public function setType($type)
    {
        $this->selectedType = $type;
    }

    public function render()
    {
        // Rimosso 'regioni' dall'eager loading
        $query = Itinerario::with('fornitori');

        // Filtra per tipologia (wedding o trip) se non è 'all'
        if ($this->selectedType !== 'all') {
            $query->where('type', $this->selectedType);
        }

        if (!empty($this->prezzoMax)) {
            $query->prezzoMax($this->prezzoMax);
        }

        if (!empty($this->durataMinima)) {
            $query->durataMinima($this->durataMinima);
        }

        return view('livewire.itinerari-index', [
            'itinerari' => $query->latest()->get()
        ]);
    }

    public function deleteItinerario($id)
    {
        $itinerario = Itinerario::find($id);
        if ($itinerario) {
            // Rimossa la chiamata alle regioni, lasciamo solo i fornitori e le voci se presenti
            $itinerario->fornitori()->detach();
            $itinerario->voci()->delete();
            $itinerario->delete();
            
            session()->flash('message', 'Itinerario eliminato con successo!');
        }
    }
}