<?php

namespace App\Livewire;

use App\Models\Regione;
use Livewire\Component;
use Livewire\WithPagination;

class RegioniIndex extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = ['search' => ['except' => '']];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $regioni = Regione::withCount('itinerari')
            ->when($this->search, function ($query) {
                $query->where('nome', 'like', '%' . $this->search . '%');
            })
            ->orderBy('nome', 'asc')
            ->get();

        return view('livewire.regioni-index', [
            'regioni' => $regioni
        ])->layout('components.layout'); // <-- Puntiamo al layout corretto di ZampApp!
    }
}