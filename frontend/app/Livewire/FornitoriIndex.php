<?php

namespace App\Livewire;

use App\Models\Fornitore;
use Livewire\Component;
use Livewire\WithPagination;

class FornitoriIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $ambitoFilter = '';

    protected $queryString = [
        'search' => ['except' => ''], 
        'ambitoFilter' => ['except' => '']
    ];

    // Reset della paginazione quando cambia la ricerca o il filtro
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedAmbitoFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $fornitori = Fornitore::when(trim($this->search), function ($query) {
                $query->where('nome', 'like', '%' . trim($this->search) . '%');
            })
            ->when($this->ambitoFilter, function ($query) {
                $query->where('ambito', $this->ambitoFilter);
            })
            ->orderBy('nome', 'asc')
            ->get();

        // Estraiamo solo gli ambiti non nulli/vuoti
        $ambiti = Fornitore::whereNotNull('ambito')
            ->where('ambito', '!=', '')
            ->distinct()
            ->pluck('ambito');

        return view('livewire.fornitori-index', [
            'fornitori' => $fornitori,
            'ambiti'    => $ambiti
        ])->layout('components.layout');
    }
}