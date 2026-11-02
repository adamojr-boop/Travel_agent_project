<?php

namespace App\Livewire;

use App\Models\Itinerario;
use Livewire\Component;

class ItinerariShow extends Component
{
    public $itinerario;

    public function mount($id)
    {
        // Carica l'itinerario con le voci analitiche (e i relativi fornitori), regioni e fornitori generali
        $this->itinerario = Itinerario::with(['regioni', 'fornitori', 'voci.fornitore'])->findOrFail($id);
    }

    public function deleteItinerario()
    {
        $this->itinerario->regioni()->detach();
        $this->itinerario->fornitori()->detach();
        $this->itinerario->voci()->delete();
        $this->itinerario->delete();

        session()->flash('message', 'Itinerario eliminato con successo!');
        return redirect()->route('itinerari.index');
    }

    public function render()
    {
        return view('livewire.itinerari-show')->layout('components.layout');
    }

    public function generaPdf()
    {
        // In futuro qui chiameremo il motore di generazione del PDF con DomPDF/Browsershot
        session()->flash('message_pdf', 'Generazione del PDF in corso per l\'itinerario: ' . $this->itinerario->titolo);
    }
}
