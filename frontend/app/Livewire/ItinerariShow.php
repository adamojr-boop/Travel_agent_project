<?php

namespace App\Livewire;

use App\Models\Itinerario;
use Livewire\Component;

class ItinerariShow extends Component
{
    public $itinerarioId;

    public function mount($id)
    {
        $this->itinerarioId = Itinerario::where('user_id', auth()->id())->findOrFail($id)->id;
    }

    public function toggleStato()
    {
        $itinerario = Itinerario::where('user_id', auth()->id())->findOrFail($this->itinerarioId);
        $itinerario->update(['stato' => $itinerario->stato === 'bozza' ? 'confermato' : 'bozza']);
    }

    public function deleteItinerario()
    {
        $itinerario = Itinerario::where('user_id', auth()->id())->findOrFail($this->itinerarioId);
        $itinerario->regioni()->detach();
        $itinerario->fornitori()->detach();
        $itinerario->voci()->delete();
        $itinerario->delete(); // giorni e servizi cadono a cascata

        session()->flash('message', 'Itinerario eliminato con successo!');
        return redirect()->route('itinerari.index');
    }

    public function render()
    {
        $itinerario = Itinerario::with(['regioni', 'giorni.servizi.fornitore'])->where('user_id', auth()->id())->findOrFail($this->itinerarioId);

        return view('livewire.itinerari-show', ['itinerario' => $itinerario])->layout('components.layout');
    }
}
