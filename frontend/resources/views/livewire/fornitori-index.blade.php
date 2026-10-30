<div class="d-flex flex-column gap-4">
    
    <!-- 1. Header -->
    <div class="card ragsy-card p-4" style="background-color: var(--lavender-veil); border: 1px solid var(--lilac-ash);">
        <h2 class="mb-1" style="color: var(--text-dark);">Gestione Fornitori</h2>
        <p class="mb-0" style="color: var(--text-dark);">Elenco dei partner e fornitori di servizi, suddivisi per ambito operativo.</p>
        <div class="row g-3 my-2">
            <div class="col-md-8">
                <label for="search" class="form-label small fw-bold" style="color: var(--text-dark);">Cerca per nome</label>
                <input wire:model.live="search" type="text" id="search" class="form-control" placeholder="Es. Hotel, Autonoleggio..." style="background-color: #ffffff; border-color: var(--lilac-ash); color: var(--text-dark);">
            </div>
            <div class="col-md-4">
                <label for="ambitoFilter" class="form-label small fw-bold" style="color: var(--text-dark);">Filtra per Ambito</label>
                <select wire:model.live="ambitoFilter" id="ambitoFilter" class="form-select" style="background-color: #ffffff; border-color: var(--lilac-ash); color: var(--text-dark);">
                    <option value="">Tutti gli ambiti</option>
                    @foreach($ambiti as $ambito)
                        <option value="{{ $ambito }}">{{ $ambito }}</option>
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
                            
                            <div class="mt-auto pt-3 border-top text-muted small" style="border-color: var(--lilac-ash) !important;">
                                Partner accreditato ZampApp
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