<div class="container py-4">
    <div class="card ragsy-card p-4 shadow-sm">

        <!-- Intestazione con Titolo e Pulsante Indietro -->
        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom" style="border-color: var(--lilac-ash) !important;">
            <div>
                <span class="badge mb-2 px-3 py-2 text-white fw-bold" style="background-color: var(--text-dark);">
                    <i class="bi bi-clock"></i> {{ $itinerario->durata_giorni }} Giorni
                </span>
                <h1 class="h2 fw-bold mb-1" style="color: var(--text-dark);">{{ $itinerario->titolo }}</h1>
            </div>
            <a href="{{ route('itinerari.index') }}" class="btn btn-outline-secondary btn-sm px-3" style="border-color: var(--lilac-ash); color: var(--text-dark);">
                <i class="bi bi-arrow-left"></i> Torna alla lista
            </a>
        </div>

        @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- 🔔 NOTIFICA GENERAZIONE PDF -->
        @if (session()->has('message_pdf'))
        <div class="alert alert-info alert-dismissible fade show mb-4" role="alert" style="background-color: var(--lavender-veil); border-color: var(--lilac-ash); color: var(--text-dark);">
            <i class="bi bi-info-circle-fill"></i> {{ session('message_pdf') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- 1. Descrizione del Viaggio -->
        <div class="mb-4">
            <div class="p-4 rounded-3" style="background-color: #ffffff; border: 1px solid var(--lilac-ash);">
                <h4 class="fw-bold mb-3" style="color: var(--text-dark);"><i class="bi bi-journal-text"></i> Descrizione del Viaggio</h4>
                <p class="text-muted mb-0" style="line-height: 1.6;">
                    {{ $itinerario->descrizione ?? 'Nessuna descrizione dettagliata inserita per questo itinerario.' }}
                </p>
            </div>
        </div>

        <!-- 2. Voci / Attività dell'Itinerario (Prezzo a persona per ogni voce) -->
        <div class="mb-4">
            <div class="p-4 rounded-3" style="background-color: var(--lavender-veil); border: 1px solid var(--lilac-ash);">
                <h5 class="fw-bold mb-3" style="color: var(--text-dark);"><i class="bi bi-list-check"></i> Attività & Servizi Inclusi</h5>

                @if($itinerario->voci && $itinerario->voci->count() > 0)
                <div class="d-flex flex-column gap-2">
                    @foreach($itinerario->voci as $voce)
                    <div class="d-flex justify-content-between align-items-center p-3 bg-white rounded-3 border" style="border-color: var(--lilac-ash) !important;">
                        <div>
                            <span class="fw-bold d-block" style="color: var(--text-dark);">{{ $voce->descrizione }}</span>
                            @if($voce->fornitore)
                            <small class="text-muted"><i class="bi bi-building"></i> {{ $voce->fornitore->nome }}</small>
                            @endif
                        </div>
                        <div class="text-end">
                            <span class="badge bg-light text-dark border px-3 py-2 fw-bold" style="border-color: var(--lilac-ash) !important;">
                                € {{ number_format($voce->prezzo_a_persona, 2, ',', '.') }} <small class="fw-normal text-muted">/ persona</small>
                            </span>
                            @if($voce->quantita_persone > 1)
                            <small class="d-block text-muted mt-1">({{ $voce->quantita_persone }} persone)</small>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-muted small mb-0">Nessuna voce di spesa dettagliata associata.</p>
                @endif
            </div>
        </div>

        <!-- 3. Regioni e Fornitori Partner -->
        <div class="row g-4 mb-4">
            <!-- Regioni -->
            <div class="col-md-6">
                <div class="p-3 rounded-3 h-100" style="background-color: var(--lavender-veil); border: 1px solid var(--lilac-ash);">
                    <h5 class="fw-bold mb-3" style="color: var(--text-dark);"><i class="bi bi-geo-alt"></i> Regioni Coinvolte</h5>
                    @if($itinerario->regioni->count() > 0)
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($itinerario->regioni as $regione)
                        <span class="badge bg-white text-dark px-3 py-2 border" style="border-color: var(--lilac-ash) !important;">
                            {{ $regione->nome }}
                        </span>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted small mb-0">Nessuna regione associata.</p>
                    @endif
                </div>
            </div>

            <!-- Fornitori Partner -->
            <div class="col-md-6">
                <div class="p-3 rounded-3 h-100" style="background-color: var(--lavender-veil); border: 1px solid var(--lilac-ash);">
                    <h5 class="fw-bold mb-3" style="color: var(--text-dark);"><i class="bi bi-building"></i> Fornitori Partner</h5>
                    @if($itinerario->fornitori->count() > 0)
                    <ul class="list-group list-group-flush bg-transparent">
                        @foreach($itinerario->fornitori as $fornitore)
                        <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 py-1" style="border-color: var(--lilac-ash);">
                            <span style="color: var(--text-dark);">{{ $fornitore->nome }}</span>
                            <span class="badge bg-secondary text-white small">{{ $fornitore->ambito }}</span>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p class="text-muted small mb-0">Nessun fornitore associato.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- 4. Riepilogo Economico (Totale in Fondo) -->
        <div class="mb-5">
            <div class="p-4 rounded-3 d-flex justify-content-between align-items-center" style="background-color: var(--lavender-veil); border: 1px solid var(--lilac-ash);">
                <div>
                    <h5 class="fw-bold mb-1" style="color: var(--text-dark);"><i class="bi bi-tag"></i> Riepilogo Economico</h5>
                    <span class="small text-muted">Prezzo totale complessivo per il pacchetto</span>
                </div>
                <div class="text-end">
                    <span class="fs-2 fw-bold" style="color: var(--text-dark);">€ {{ number_format($itinerario->prezzo, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Pulsanti di Azione in Fondo -->
        <div class="d-flex justify-content-between align-items-center pt-3 border-top" style="border-color: var(--lilac-ash) !important;">
            <a href="{{ route('itinerari.index') }}" class="btn btn-light px-4" style="border: 1px solid var(--lilac-ash);">
                Indietro
            </a>
            <div class="d-flex gap-2">
                <!-- Pulsante Genera / Scarica PDF -->
                <button wire:click="generaPdf" class="btn px-4 text-white fw-bold shadow-sm" style="background-color: #6c5ce7;" title="Genera il documento PDF per il cliente">
                    <i class="bi bi-file-earmark-pdf"></i> Genera PDF
                </button>

                <a href="{{ route('itinerari.edit', $itinerario->id) }}" class="btn px-4 text-white fw-bold" style="background-color: var(--text-dark);">
                    <i class="bi bi-pencil-square"></i> Modifica
                </a>

                <button wire:click="deleteItinerario" onclick="return confirm('Sei sicura di voler eliminare questo itinerario?')" class="btn btn-outline-danger px-4">
                    <i class="bi bi-trash"></i> Elimina
                </button>
            </div>
        </div>

    </div>
</div>