<?php

namespace App\Livewire;

use App\Models\Itinerario;
use App\Models\Fornitore;
use Livewire\Component;

class ItinerariEdit extends Component
{
    public $itinerarioId;
    public $type; // 💍 Aggiunto per gestire trip o wedding
    public $titolo;
    public $descrizione;
    public $prezzo;
    public $durata_giorni;

    public $selectedFornitori = [];

    public function mount($id)
    {
        // Rimosso 'regioni' dall'eager loading
        $itinerario = Itinerario::with(['fornitori'])->findOrFail($id);

        $this->itinerarioId = $itinerario->id;
        $this->type = $itinerario->type ?? 'trip'; // Carica il tipo esistente
        $this->titolo = $itinerario->titolo;
        $this->descrizione = $itinerario->descrizione;
        $this->prezzo = $itinerario->prezzo;
        $this->durata_giorni = $itinerario->durata_giorni;

        $this->selectedFornitori = $itinerario->fornitori->pluck('id')->toArray();
    }

    public function update()
    {
        $this->validate([
            'type' => 'required|in:trip,wedding', // Validazione del tipo
            'titolo' => 'required|string|max:255',
            'prezzo' => 'required|numeric',
            'durata_giorni' => 'required|integer',
        ]);

        $itinerario = Itinerario::findOrFail($this->itinerarioId);
        $itinerario->update([
            'type' => $this->type, // Aggiorna il tipo
            'titolo' => $this->titolo,
            'descrizione' => $this->descrizione,
            'prezzo' => $this->prezzo,
            'durata_giorni' => $this->durata_giorni,
        ]);

        // Rimossa la sincronizzazione delle regioni
        $itinerario->fornitori()->sync($this->selectedFornitori);

        session()->flash('message', 'Itinerario aggiornato con successo!');
        return redirect()->route('itinerari.index');
    }

    public function render()
    {
        return view('livewire.itinerari-edit', [
            'tuttiIFornitori' => Fornitore::all(),
        ])->layout('components.layout');
    }
}
