<div class="d-flex flex-column gap-4">
    
    <!-- 1. Header -->
    <div class="card ragsy-card p-4" style="background-color: var(--lavender-veil); border: 1px solid var(--lilac-ash);">
        <div class="d-flex justify-content-between align-items-start">
            <h2 class="mb-1" style="color: var(--text-dark);">Gestione Fornitori</h2>
            <button type="button" wire:click="$toggle('mostraForm')" class="btn btn-sm text-white fw-bold" style="background-color: var(--text-dark);"><i class="bi bi-plus-circle"></i> Nuovo fornitore</button>
        </div>
        @if($mostraForm)
            <form wire:submit="salvaFornitore" class="row g-2 my-3 p-3 rounded-3 bg-white" style="border: 1px solid var(--lilac-ash);">
                <div class="col-md-4"><input wire:model="nuovoNome" type="text" class="form-control form-control-sm" placeholder="Nome *">@error('nuovoNome')<small class="text-danger">{{ $message }}</small>@enderror</div>
                <div class="col-md-2"><input wire:model="nuovoAmbito" type="text" class="form-control form-control-sm" placeholder="Ambito *"></div>
                <div class="col-md-3"><input wire:model="nuovaCitta" type="text" class="form-control form-control-sm" placeholder="Città"></div>
                <div class="col-md-3">
                    <select wire:model="nuovaRegione" class="form-select form-select-sm">
                        <option value="">Regione</option>
                        @foreach($regioni as $r)<option value="{{ $r->id }}">{{ $r->nome }}</option>@endforeach
                    </select>
                </div>
                <div class="col-12"><textarea wire:model="nuovaDescrizione" rows="2" class="form-control form-control-sm" placeholder="Descrizione"></textarea></div>
                <div class="col-12 text-end"><button type="submit" class="btn btn-sm btn-success">Salva</button></div>
            </form>
        @endif
        <p class="mb-0" style="color: var(--text-dark);">Elenco dei partner e fornitori di servizi, suddivisi per ambito operativo.</p>
        <div class="row g-3 my-2">
            <div class="col-md-5">
                <label for="search" class="form-label small fw-bold" style="color: var(--text-dark);">Cerca per nome</label>
                <input wire:model.live="search" type="text" id="search" class="form-control" placeholder="Es. Hotel, Autonoleggio..." style="background-color: #ffffff; border-color: var(--lilac-ash); color: var(--text-dark);">
            </div>
            <div class="col-md-3">
                <label for="ambitoFilter" class="form-label small fw-bold" style="color: var(--text-dark);">Filtra per Ambito</label>
                <select wire:model.live="ambitoFilter" id="ambitoFilter" class="form-select" style="background-color: #ffffff; border-color: var(--lilac-ash); color: var(--text-dark);">
                    <option value="">Tutti gli ambiti</option>
                    @foreach($ambiti as $ambito)
                        <option value="{{ $ambito }}">{{ $ambito }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="regioneFilter" class="form-label small fw-bold" style="color: var(--text-dark);">Filtra per Regione</label>
                <select wire:model.live="regioneFilter" id="regioneFilter" class="form-select" style="background-color: #ffffff; border-color: var(--lilac-ash); color: var(--text-dark);">
                    <option value="">Tutte le regioni</option>
                    @foreach($regioni as $r)
                        <option value="{{ $r->id }}">{{ $r->nome }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- 3. Griglia dei Fornitori -->
    <div class="card ragsy-card p-4" style="background-color: var(--lavender-veil); border: 1px solid var(--lilac-ash);">
        <h5 class="fw-bold mb-3" style="color: var(--text-dark);">Elenco Fornitori</h5>
        
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @forelse($fornitori as $fornitore)
                <div class="col">
                    <div class="card h-100 shadow-sm rounded-3" style="background-color: #ffffff; border: 1px solid var(--lilac-ash);">
                        <div class="card-body d-flex flex-column p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h4 class="card-title fw-bold mb-0" style="color: var(--text-dark);">{{ $fornitore->nome }}</h4>
                                <span class="badge px-3 py-2" style="background-color: var(--lavender-veil); color: var(--text-dark); border: 1px solid var(--lilac-ash);">
                                    {{ $fornitore->ambito }}
                                </span>
                            </div>
                            
                            <div class="mt-auto pt-3 border-top small" style="border-color: var(--lilac-ash) !important;">
                                @if($fornitore->citta)
                                    <div class="text-muted mb-2"><i class="bi bi-geo-alt"></i> {{ $fornitore->citta }}</div>
                                @endif
                                <select wire:change="setRegione({{ $fornitore->id }}, $event.target.value)" class="form-select form-select-sm" aria-label="Regione di {{ $fornitore->nome }}" style="background-color: #ffffff; border-color: var(--lilac-ash); color: var(--text-dark);">
                                    <option value="">Regione non assegnata</option>
                                    @foreach($regioni as $r)
                                        <option value="{{ $r->id }}" @selected($fornitore->regione_id === $r->id)>{{ $r->nome }}</option>
                                    @endforeach
                                </select>
                                <div class="text-end mt-2">
                                    <button type="button" wire:click="eliminaFornitore({{ $fornitore->id }})" wire:confirm="Eliminare {{ $fornitore->nome }}?" class="btn btn-link btn-sm text-danger p-0"><i class="bi bi-trash"></i> Elimina</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert text-center py-4 rounded-3 m-0" style="background-color: #ffffff; border: 1px solid var(--lilac-ash); color: var(--text-dark);" role="alert">
                        Nessun fornitore trovato con i criteri selezionati.
                    </div>
                </div>
            @endforelse
        </div>
    </div>

</div>