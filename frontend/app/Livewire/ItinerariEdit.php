<?php

namespace App\Livewire;

use App\Models\Itinerario;
use App\Models\Regione;
use App\Models\Fornitore;
use Livewire\Component;

class ItinerariEdit extends Component
{
    public $itinerarioId;
    public $titolo;
    public $descrizione;
    public $prezzo;
    public $durata_giorni;
    
    public $selectedRegioni = [];
    public $selectedFornitori = [];

    public function mount($id)
    {
        $itinerario = Itinerario::with(['regioni', 'fornitori'])->findOrFail($id);
        
        $this->itinerarioId = $itinerario->id;
        $this->titolo = $itinerario->titolo;
        $this->descrizione = $itinerario->descrizione;
        $this->prezzo = $itinerario->prezzo;
        $this->durata_giorni = $itinerario->durata_giorni;
        
        $this->selectedRegioni = $itinerario->regioni->pluck('id')->toArray();
        $this->selectedFornitori = $itinerario->fornitori->pluck('id')->toArray();
    }

    public function update()
    {
        $this->validate([
            'titolo' => 'required|string|max:255',
            'prezzo' => 'required|numeric',
            'durata_giorni' => 'required|integer',
        ]);

        $itinerario = Itinerario::findOrFail($this->itinerarioId);
        $itinerario->update([
            'titolo' => $this->titolo,
            'descrizione' => $this->descrizione,
            'prezzo' => $this->prezzo,
            'durata_giorni' => $this->durata_giorni,
        ]);

        $itinerario->regioni()->sync($this->selectedRegioni);
        $itinerario->fornitori()->sync($this->selectedFornitori);

        session()->flash('message', 'Itinerario aggiornato con successo!');
        return redirect()->route('itinerari.index');
    }

    public function render()
    {
        return view('livewire.itinerari-edit', [
            'tutteLeRegioni' => Regione::all(),
            'tuttiIFornitori' => Fornitore::all(),
        ])->layout('components.layout');
    }
}