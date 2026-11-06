<?php

namespace App\Livewire;

use App\Models\Fornitore;
use App\Models\Itinerario;
use App\Models\Regione;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layout')] 
class ItinerariIndex extends Component
{
    public $selectedType = 'all'; // Filtro: 'all', 'wedding', 'trip'
    public $regione = '';
    public $fornitore = '';

    // Metodo per cambiare il tab/filtro attivo
    public function setType($type)
    {
        $this->selectedType = $type;
    }

    public function render()
    {
        $query = Itinerario::with(['fornitori', 'regioni'])->where('user_id', auth()->id());

        // Filtra per tipologia (wedding o trip) se non è 'all'
        if ($this->selectedType !== 'all') {
            $query->where('type', $this->selectedType);
        }

        if (!empty($this->regione)) {
            $query->whereHas('regioni', fn ($q) => $q->where('regioni.id', $this->regione));
        }

        if (!empty($this->fornitore)) {
            $query->whereHas('fornitori', fn ($q) => $q->where('fornitori.id', $this->fornitore));
        }

        return view('livewire.itinerari-index', [
            'itinerari' => $query->latest()->get(),
            'regioniOpzioni' => Regione::whereHas('itinerari', fn ($q) => $q->where('user_id', auth()->id()))->orderBy('nome')->get(['id', 'nome']),
            'fornitoriOpzioni' => Fornitore::where('user_id', auth()->id())->whereHas('itinerari', fn ($q) => $q->where('itinerari.user_id', auth()->id()))->orderBy('nome')->get(['id', 'nome']),
        ]);
    }

    public function deleteItinerario($id)
    {
        $itinerario = Itinerario::where('user_id', auth()->id())->findOrFail($id);
        if ($itinerario) {
            $itinerario->regioni()->detach();
            $itinerario->fornitori()->detach();
            $itinerario->voci()->delete();
            $itinerario->delete();
            
            session()->flash('message', 'Itinerario eliminato con successo!');
        }
    }
}