<div class="d-flex flex-column gap-4">
    
    <!-- 1. Sezione Header -->
    <div class="card ragsy-card p-4" style="background-color: var(--lavender-veil); border: 1px solid var(--lilac-ash);">
        <h2 class="mb-1" style="color: var(--text-dark);">Filtra Regioni</h2>
        <div class="row g-3">
            <div class="col-12">
                <label for="search" class="form-label small fw-bold" style="color: var(--text-dark);">Cerca per nome</label>
                <input wire:model.live="search" type="text" id="search" class="form-control" placeholder="Es. Toscana, Sicilia..." style="background-color: #ffffff; border-color: var(--lilac-ash); color: var(--text-dark);">
            </div>
        </div>
    </div>

    <!-- 3. Sezione Griglia Regioni (Card separata con card interne bianche) -->
    <div class="card ragsy-card p-4" style="background-color: var(--lavender-veil); border: 1px solid var(--lilac-ash);">
        <h5 class="fw-bold mb-3" style="color: var(--text-dark);">Elenco Regioni</h5>
        
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @forelse($regioni as $regione)
                <div class="col">
                    <div class="card h-100 shadow-sm rounded-3" style="background-color: #ffffff; border: 1px solid var(--lilac-ash);">
                        <div class="card-body d-flex flex-column p-4">
                            <h4 class="card-title fw-bold mb-3" style="color: var(--text-dark);">{{ $regione->nome }}</h4>
                            
                            <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center" style="border-color: var(--lilac-ash) !important;">
                                <span class="text-muted small">Itinerari collegati:</span>
                                <span class="badge px-3 py-2 fs-6" style="background-color: var(--lavender-veil); color: var(--text-dark); border: 1px solid var(--lilac-ash);">
                                    {{ $regione->itinerari_count }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert text-center py-4 rounded-3 m-0" style="background-color: #ffffff; border: 1px solid var(--lilac-ash); color: var(--text-dark);" role="alert">
                        Nessuna regione trovata con il criterio di ricerca inserito.
                    </div>
                </div>
            @endforelse
        </div>
    </div>

</div>