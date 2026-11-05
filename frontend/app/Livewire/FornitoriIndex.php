<?php

namespace App\Livewire;

use App\Models\Fornitore;
use App\Models\Regione;
use Livewire\Component;
use Livewire\WithPagination;

class FornitoriIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $ambitoFilter = '';
    public $regioneFilter = '';

    protected $queryString = [
        'search' => ['except' => ''], 
        'ambitoFilter' => ['except' => ''],
        'regioneFilter' => ['except' => ''],
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

    public function updatedRegioneFilter()
    {
        $this->resetPage();
    }

    // Assegnazione manuale della regione a un fornitore
    public function setRegione($fornitoreId, $regioneId)
    {
        Fornitore::whereKey($fornitoreId)->update(['regione_id' => $regioneId ?: null]);
    }

    public bool $mostraForm = false;
    public $nuovoNome = '';
    public $nuovoAmbito = 'Alloggi';
    public $nuovaCitta = '';
    public $nuovaRegione = '';
    public $nuovaDescrizione = '';

    public function salvaFornitore()
    {
        $dati = $this->validate([
            'nuovoNome' => 'required|string|max:255',
            'nuovoAmbito' => 'required|string|max:255',
            'nuovaCitta' => 'nullable|string|max:255',
            'nuovaRegione' => 'nullable|exists:regioni,id',
            'nuovaDescrizione' => 'nullable|string',
        ]);

        Fornitore::create([
            'nome' => $dati['nuovoNome'],
            'ambito' => $dati['nuovoAmbito'],
            'citta' => $dati['nuovaCitta'] ?: null,
            'regione_id' => $dati['nuovaRegione'] ?: null,
            'descrizione' => $dati['nuovaDescrizione'] ?: null,
        ]);

        $this->reset(['mostraForm', 'nuovoNome', 'nuovaCitta', 'nuovaRegione', 'nuovaDescrizione']);
    }

    public function eliminaFornitore($id)
    {
        $fornitore = Fornitore::findOrFail($id);
        $fornitore->itinerari()->detach();
        $fornitore->delete();
    }

    public function render()
    {
        $fornitori = Fornitore::with('regione')
            ->when(trim($this->search), function ($query) {
                $query->where('nome', 'like', '%' . trim($this->search) . '%');
            })
            ->when($this->ambitoFilter, function ($query) {
                $query->where('ambito', $this->ambitoFilter);
            })
            ->when($this->regioneFilter, function ($query) {
                $query->where('regione_id', $this->regioneFilter);
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
            'ambiti'    => $ambiti,
            'regioni'   => Regione::orderBy('nome')->get(['id', 'nome']),
        ])->layout('components.layout');
    }
}