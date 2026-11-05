@php $in = 'background-color: #ffffff; border-color: var(--lilac-ash); color: var(--text-dark);'; @endphp
<div class="container py-4">
    <form wire:submit="save" class="card ragsy-card p-4 shadow-sm d-flex flex-column gap-4">

        <div class="d-flex justify-content-between align-items-center pb-3 border-bottom" style="border-color: var(--lilac-ash) !important;">
            <h2 class="h3 mb-0" style="color: var(--text-dark);">{{ $itinerarioId ? 'Modifica itinerario' : 'Nuovo itinerario' }}</h2>
            <a href="{{ $itinerarioId ? route('itinerari.show', $itinerarioId) : route('itinerari.index') }}" class="btn btn-outline-secondary btn-sm px-3">Annulla</a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger mb-0">Controlla i campi evidenziati: {{ $errors->first() }}</div>
        @endif

        <!-- Dati generali -->
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label fw-bold">Titolo</label>
                <input wire:model="titolo" type="text" class="form-control" style="{{ $in }}">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold">Tipo</label>
                <select wire:model="type" class="form-select" style="{{ $in }}">
                    <option value="trip">Trip</option>
                    <option value="wedding">Wedding</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold">Stato</label>
                <select wire:model="stato" class="form-select" style="{{ $in }}">
                    <option value="bozza">Bozza</option>
                    <option value="confermato">Confermato</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Data inizio</label>
                <input wire:model="data_inizio" type="date" class="form-control" style="{{ $in }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Data fine</label>
                <input wire:model="data_fine" type="date" class="form-control" style="{{ $in }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Persone</label>
                <input wire:model="persone" type="number" min="1" class="form-control" style="{{ $in }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Valuta</label>
                <input wire:model="valuta" type="text" maxlength="3" class="form-control text-uppercase" style="{{ $in }}">
            </div>
        </div>

        <!-- Giorni -->
        @foreach($giorni as $i => $g)
            <div class="p-3 rounded-3" wire:key="giorno-{{ $i }}" style="background-color: var(--lavender-veil); border: 1px solid var(--lilac-ash);">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 fw-bold">Giorno {{ $g['numero'] }}</h5>
                    <button type="button" wire:click="rimuoviGiorno({{ $i }})" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i> Rimuovi giorno</button>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Data</label>
                        <input wire:model="giorni.{{ $i }}.data" type="date" class="form-control form-control-sm" style="{{ $in }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Città</label>
                        <input wire:model="giorni.{{ $i }}.citta" type="text" class="form-control form-control-sm" placeholder="Es. Rome" style="{{ $in }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Testo di benvenuto</label>
                        <textarea wire:model="giorni.{{ $i }}.intro" rows="2" class="form-control form-control-sm" style="{{ $in }}"></textarea>
                    </div>
                </div>

                @foreach($g['servizi'] as $j => $s)
                    <div class="p-3 mb-3 rounded-3 bg-white" wire:key="servizio-{{ $i }}-{{ $j }}" style="border: 1px solid var(--lilac-ash);">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Tipo</label>
                                <select wire:model="giorni.{{ $i }}.servizi.{{ $j }}.tipo" class="form-select form-select-sm" style="{{ $in }}">
                                    @foreach($tipiServizio as $t)<option value="{{ $t }}">{{ $t }}</option>@endforeach
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label small fw-bold">Titolo</label>
                                <input wire:model="giorni.{{ $i }}.servizi.{{ $j }}.titolo" type="text" class="form-control form-control-sm" style="{{ $in }}">
                            </div>

                            @if($s['tipo'] === 'Accommodation')
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Hotel (fornitore)</label>
                                    <select wire:model="giorni.{{ $i }}.servizi.{{ $j }}.fornitore_id" class="form-select form-select-sm" style="{{ $in }}">
                                        <option value="">— nessuno —</option>
                                        @foreach($tuttiIFornitori as $f)<option value="{{ $f->id }}">{{ $f->nome }}</option>@endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold">Notti</label>
                                    <input wire:model="giorni.{{ $i }}.servizi.{{ $j }}.notti" type="number" min="1" class="form-control form-control-sm" style="{{ $in }}">
                                </div>
                            @else
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">Fornitore</label>
                                    <select wire:model="giorni.{{ $i }}.servizi.{{ $j }}.fornitore_id" class="form-select form-select-sm" style="{{ $in }}">
                                        <option value="">— nessuno —</option>
                                        @foreach($tuttiIFornitori as $f)<option value="{{ $f->id }}">{{ $f->nome }}</option>@endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold">Partecipanti</label>
                                    <input wire:model="giorni.{{ $i }}.servizi.{{ $j }}.partecipanti" type="number" min="1" class="form-control form-control-sm" style="{{ $in }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold">Durata</label>
                                    <input wire:model="giorni.{{ $i }}.servizi.{{ $j }}.durata" type="text" placeholder="4 hours" class="form-control form-control-sm" style="{{ $in }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold">Prezzo / persona</label>
                                    <input wire:model="giorni.{{ $i }}.servizi.{{ $j }}.prezzo_a_persona" type="number" step="0.01" min="0" class="form-control form-control-sm" style="{{ $in }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold">Valuta</label>
                                    <input wire:model="giorni.{{ $i }}.servizi.{{ $j }}.valuta" type="text" maxlength="3" placeholder="USD" class="form-control form-control-sm text-uppercase" style="{{ $in }}">
                                </div>
                            @endif

                            <div class="col-md-2">
                                <label class="form-label small fw-bold">Opzione n.</label>
                                <input wire:model="giorni.{{ $i }}.servizi.{{ $j }}.opzione" type="number" min="1" class="form-control form-control-sm" style="{{ $in }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <div class="form-check">
                                    <input wire:model="giorni.{{ $i }}.servizi.{{ $j }}.opzionale" type="checkbox" class="form-check-input" id="opz-{{ $i }}-{{ $j }}">
                                    <label class="form-check-label small" for="opz-{{ $i }}-{{ $j }}">Optional</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Descrizione</label>
                                <textarea wire:model="giorni.{{ $i }}.servizi.{{ $j }}.descrizione" rows="2" class="form-control form-control-sm" style="{{ $in }}"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Inclusioni (una per riga)</label>
                                <textarea wire:model="giorni.{{ $i }}.servizi.{{ $j }}.inclusioni" rows="3" class="form-control form-control-sm" style="{{ $in }}"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Esclusioni (una per riga)</label>
                                <textarea wire:model="giorni.{{ $i }}.servizi.{{ $j }}.esclusioni" rows="3" class="form-control form-control-sm" style="{{ $in }}"></textarea>
                            </div>
                        </div>
                        <div class="text-end mt-2">
                            <button type="button" wire:click="rimuoviServizio({{ $i }}, {{ $j }})" class="btn btn-link btn-sm text-danger"><i class="bi bi-x-circle"></i> Rimuovi servizio</button>
                        </div>
                    </div>
                @endforeach

                <button type="button" wire:click="aggiungiServizio({{ $i }})" class="btn btn-outline-secondary btn-sm"><i class="bi bi-plus-circle"></i> Aggiungi servizio</button>
            </div>
        @endforeach

        <div>
            <button type="button" wire:click="aggiungiGiorno" class="btn btn-outline-secondary"><i class="bi bi-plus-circle"></i> Aggiungi giorno</button>
        </div>

        <div class="d-flex justify-content-end gap-2 pt-3 border-top" style="border-color: var(--lilac-ash) !important;">
            <button type="submit" class="btn px-4 text-white fw-bold" style="background-color: var(--text-dark);">
                <i class="bi bi-check-lg"></i> Salva
            </button>
        </div>
    </form>
</div>
