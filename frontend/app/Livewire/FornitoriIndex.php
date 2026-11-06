<?php

namespace App\Livewire;

use App\Models\Fornitore;
use App\Models\Regione;
use Illuminate\Validation\Rule;
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

    public bool $mostraForm = false;
    public $nuovoNome = '';
    public $nuovoAmbito = '';
    public $nuovaCitta = '';
    public $nuovaRegione = '';
    public $nuovaDescrizione = '';

    public function toggleForm()
    {
        if ($this->mostraForm) {
            $this->annullaInserimento();
        } else {
            $this->mostraForm = true;
        }
    }

    public function annullaInserimento()
    {
        $this->reset(['mostraForm', 'nuovoNome', 'nuovoAmbito', 'nuovaCitta', 'nuovaRegione', 'nuovaDescrizione']);
        $this->resetValidation();
    }

    public function salvaFornitore()
    {
        $dati = $this->validate([
            'nuovoNome' => 'required|string|max:255',
            'nuovoAmbito' => ['required', Rule::in(array_keys(Fornitore::AMBITI))],
            'nuovaCitta' => 'nullable|string|max:255',
            'nuovaRegione' => 'nullable|exists:regioni,id',
            'nuovaDescrizione' => 'nullable|string',
        ]);

        Fornitore::create([
            'nome' => $dati['nuovoNome'],
            'user_id' => auth()->id(),
            'ambito' => $dati['nuovoAmbito'],
            'citta' => $dati['nuovaCitta'] ?: null,
            'regione_id' => $dati['nuovaRegione'] ?: null,
            'descrizione' => $dati['nuovaDescrizione'] ?: null,
        ]);

        $this->annullaInserimento();
    }

    public function eliminaFornitore($id)
    {
        $fornitore = Fornitore::where('user_id', auth()->id())->findOrFail($id);
        $fornitore->itinerari()->detach();
        $fornitore->delete();
    }

    public function render()
    {
        $fornitori = Fornitore::with('regione')
            ->where('user_id', auth()->id())
            ->when(trim($this->search), function ($query) {
                $query->where('nome', 'like', '%' . trim($this->search) . '%');
            })
            ->when($this->ambitoFilter, function ($query) {
                $query->whereIn('ambito', [
                    $this->ambitoFilter,
                    Fornitore::AMBITI[$this->ambitoFilter] ?? $this->ambitoFilter,
                ]);
            })
            ->when($this->regioneFilter, function ($query) {
                $query->where('regione_id', $this->regioneFilter);
            })
            ->orderBy('nome', 'asc')
            ->get();

        return view('livewire.fornitori-index', [
            'fornitori' => $fornitori,
            'ambiti'    => Fornitore::AMBITI,
            'regioni'   => Regione::orderBy('nome')->get(['id', 'nome']),
        ])->layout('components.layout');
    }
}