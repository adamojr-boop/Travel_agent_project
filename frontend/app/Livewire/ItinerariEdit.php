<?php

namespace App\Livewire;

use App\Http\Controllers\Api\Internal\ItinerarioController;
use App\Models\Fornitore;
use App\Models\Itinerario;
use App\Support\RegioniItinerario;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ItinerariEdit extends Component
{
    public $itinerarioId = null;
    public $type = 'trip';
    public $titolo = '';
    public $stato = 'bozza';
    public $codice = '';
    public $data_inizio = '';
    public $data_fine = '';
    public $persone = '';
    public $valuta = 'USD';

    public array $giorni = [];

    public function mount($id = null)
    {
        if (!$id) {
            $this->giorni = [$this->giornoVuoto(1)];
            return;
        }

        $it = Itinerario::with('giorni.servizi')->where('user_id', auth()->id())->findOrFail($id);
        $this->itinerarioId = $it->id;
        $this->type = $it->type ?? 'trip';
        $this->titolo = $it->titolo;
        $this->stato = $it->stato;
        $this->codice = $it->codice ?? '';
        $this->data_inizio = $it->data_inizio?->toDateString() ?? '';
        $this->data_fine = $it->data_fine?->toDateString() ?? '';
        $this->persone = $it->persone ?? '';
        $this->valuta = $it->valuta;

        $this->giorni = $it->giorni->map(fn ($g) => [
            'numero' => $g->numero,
            'data' => $g->data?->toDateString() ?? '',
            'citta' => $g->citta ?? '',
            'intro' => $g->intro ?? '',
            'servizi' => $g->servizi->map(fn ($s) => [
                'tipo' => $s->tipo,
                'titolo' => $s->titolo,
                'opzione' => $s->opzione ?? '',
                'opzionale' => (bool) $s->opzionale,
                'descrizione' => $s->descrizione ?? '',
                'partecipanti' => $s->partecipanti ?? '',
                'durata' => $s->durata ?? '',
                'notti' => $s->notti ?? '',
                'inclusioni' => implode("\n", $s->inclusioni ?? []),
                'esclusioni' => implode("\n", $s->esclusioni ?? []),
                'prezzo_a_persona' => $s->prezzo_a_persona ?? '',
                'valuta' => $s->valuta ?? '',
                'fornitore_id' => $s->fornitore_id ?? '',
            ])->all(),
        ])->all();
    }

    private function giornoVuoto(int $numero): array
    {
        return ['numero' => $numero, 'data' => '', 'citta' => '', 'intro' => '', 'servizi' => []];
    }

    private function servizioVuoto(): array
    {
        return [
            'tipo' => 'Private Transfer', 'titolo' => '', 'opzione' => '', 'opzionale' => false,
            'descrizione' => '', 'partecipanti' => '', 'durata' => '', 'notti' => '',
            'inclusioni' => '', 'esclusioni' => '', 'prezzo_a_persona' => '', 'valuta' => '', 'fornitore_id' => '',
        ];
    }

    public function aggiungiGiorno()
    {
        $this->giorni[] = $this->giornoVuoto(count($this->giorni) + 1);
    }

    public function rimuoviGiorno($i)
    {
        unset($this->giorni[$i]);
        $this->giorni = array_values($this->giorni);
        foreach ($this->giorni as $k => $g) {
            $this->giorni[$k]['numero'] = $k + 1;
        }
    }

    public function aggiungiServizio($i)
    {
        $this->giorni[$i]['servizi'][] = $this->servizioVuoto();
    }

    public function rimuoviServizio($i, $j)
    {
        unset($this->giorni[$i]['servizi'][$j]);
        $this->giorni[$i]['servizi'] = array_values($this->giorni[$i]['servizi']);
    }

    private function righe(?string $testo): ?array
    {
        $righe = array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $testo))));
        return $righe ?: null;
    }

    private function nullo($v)
    {
        return ($v === '' || $v === null) ? null : $v;
    }

    public function save()
    {
        $this->validate([
            'type' => 'required|in:trip,wedding',
            'stato' => 'required|in:bozza,confermato',
            'titolo' => 'required|string|max:255',
            'data_inizio' => 'nullable|date',
            'data_fine' => 'nullable|date|after_or_equal:data_inizio',
            'persone' => 'nullable|integer|min:1',
            'valuta' => 'required|string|size:3',
            'giorni' => 'required|array|min:1',
            'giorni.*.data' => 'nullable|date',
            'giorni.*.servizi.*.tipo' => 'required|in:' . implode(',', ItinerarioController::TIPI_SERVIZIO),
            'giorni.*.servizi.*.titolo' => 'required|string|max:255',
            'giorni.*.servizi.*.prezzo_a_persona' => 'nullable|numeric|min:0',
            'giorni.*.servizi.*.fornitore_id' => ['nullable', Rule::exists('fornitori', 'id')->where('user_id', auth()->id())],
        ]);

        $it = DB::transaction(function () {
            $n = count($this->giorni);
            $dati = [
                'type' => $this->type,
                'stato' => $this->stato,
                'titolo' => $this->titolo,
                'data_inizio' => $this->nullo($this->data_inizio),
                'data_fine' => $this->nullo($this->data_fine),
                'persone' => $this->nullo($this->persone),
                'valuta' => strtoupper($this->valuta),
                'durata_giorni' => $n,
                'notti' => max($n - 1, 0),
            ];

            $it = $this->itinerarioId
                ? tap(Itinerario::where('user_id', auth()->id())->findOrFail($this->itinerarioId))->update($dati)
                : Itinerario::create($dati + ['prezzo' => 0, 'user_id' => auth()->id()]);

            $it->giorni()->delete();

            foreach ($this->giorni as $k => $g) {
                $giorno = $it->giorni()->create([
                    'numero' => $k + 1,
                    'data' => $this->nullo($g['data']),
                    'citta' => $this->nullo($g['citta']),
                    'intro' => $this->nullo($g['intro']),
                ]);

                foreach ($g['servizi'] as $o => $s) {
                    $giorno->servizi()->create([
                        'ordine' => $o + 1,
                        'tipo' => $s['tipo'],
                        'titolo' => $s['titolo'],
                        'opzione' => $this->nullo($s['opzione']),
                        'opzionale' => (bool) $s['opzionale'],
                        'descrizione' => $this->nullo($s['descrizione']),
                        'partecipanti' => $this->nullo($s['partecipanti']),
                        'durata' => $this->nullo($s['durata']),
                        'notti' => $this->nullo($s['notti']),
                        'inclusioni' => $this->righe($s['inclusioni']),
                        'esclusioni' => $this->righe($s['esclusioni']),
                        'prezzo_a_persona' => $this->nullo($s['prezzo_a_persona']),
                        'valuta' => $this->nullo($s['valuta']),
                        'fornitore_id' => $this->nullo($s['fornitore_id']),
                    ]);
                }
            }

            $it->fornitori()->sync(
                $it->giorni()->with('servizi')->get()->flatMap->servizi->pluck('fornitore_id')->filter()->unique()
            );
            RegioniItinerario::sincronizza($it);

            return $it;
        });

        session()->flash('message', 'Itinerario salvato con successo!');
        return redirect()->route('itinerari.show', $it->id);
    }

    public function render()
    {
        return view('livewire.itinerari-edit', [
            'tipiServizio' => ItinerarioController::TIPI_SERVIZIO,
            'tuttiIFornitori' => Fornitore::where('user_id', auth()->id())->orderBy('nome')->get(['id', 'nome']),
        ])->layout('components.layout');
    }
}
