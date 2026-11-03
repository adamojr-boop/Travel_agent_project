<div class="d-flex flex-column gap-4">
    <div class="card ragsy-card p-4 d-flex flex-column gap-4">
        
        <!-- Intestazione della sezione -->
        <div class="pb-2 border-bottom" style="border-color: var(--lilac-ash) !important;">
            <h2 class="mb-1" style="color: var(--text-dark);">Esplora gli Itinerari</h2>
            <p class="mb-0 text-muted">Gestisci e filtra le tue proposte di viaggio e matrimoni.</p>
        </div>

        <!-- Sezione Filtri Avanzati (Tipo, Prezzo e Durata) -->
        <div class="filters-panel p-4">
            <div class="row g-3">
                <!-- Filtro per Tipologia di Viaggio (Menu a Tendina) -->
                <div class="col-md-4">
                    <label for="selectedType" class="form-label fw-bold" style="color: var(--text-dark);">Tipo di Viaggio</label>
                    <select wire:model.live="selectedType" id="selectedType" class="form-select" style="background-color: #ffffff; border-color: var(--lilac-ash); color: var(--text-dark);">
                        <option value="all">Tutte le tipologie</option>
                        <option value="trip">✈️ Trips (Viaggi Standard)</option>
                        <option value="wedding">💍 Weddings (Matrimoni / Nozze)</option>
                    </select>
                </div>

                <!-- Filtro per Prezzo Massimo -->
                <div class="col-md-4">
                    <label for="prezzoMax" class="form-label fw-bold" style="color: var(--text-dark);">Prezzo Massimo (€)</label>
                    <input wire:model.live="prezzoMax" type="number" id="prezzoMax" class="form-control" placeholder="Es. 2500" style="background-color: #ffffff; border-color: var(--lilac-ash); color: var(--text-dark);">
                </div>

                <!-- Filtro per Durata Minima -->
                <div class="col-md-4">
                    <label for="durataMinima" class="form-label fw-bold" style="color: var(--text-dark);">Durata Minima (Giorni)</label>
                    <input wire:model.live="durataMinima" type="number" id="durataMinima" class="form-control" placeholder="Es. 7" style="background-color: #ffffff; border-color: var(--lilac-ash); color: var(--text-dark);">
                </div>
            </div>
        </div>
    </div>

    <div class="card ragsy-card p-4">
        <!-- Griglia degli Itinerari -->
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @forelse($itinerari as $itinerario)
                <div class="col">
                    <div class="card h-100 shadow-sm rounded-3 itinerary-card" style="background-color: #ffffff; border: 1px solid var(--lilac-ash);">
                        <div class="card-body d-flex flex-column p-4">
                            
                            <!-- Titolo e Badge Tipologia -->
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                <h4 class="card-title fw-bold mb-0" style="color: var(--text-dark);">{{ $itinerario->titolo }}</h4>
                                <span class="badge px-2 py-1 text-white fw-bold {{ $itinerario->type === 'wedding' ? 'bg-danger' : 'bg-primary' }}">
                                    {{ $itinerario->type === 'wedding' ? '💍 Wedding' : '✈️ Trip' }}
                                </span>
                            </div>
                            
                            <!-- Descrizione -->
                            <p class="card-text small flex-grow-1 mb-3 text-muted" style="line-height: 1.5;">
                                {{ Str::limit($itinerario->descrizione, 120) }}
                            </p>
                            
                            <!-- Prezzo e Durata -->
                            <div class="d-flex justify-content-between align-items-center mb-3 pt-3 border-top" style="border-color: var(--lilac-ash);">
                                <span class="fw-bold fs-4" style="color: var(--text-dark);">€ {{ number_format($itinerario->prezzo, 2, ',', '.') }}</span>
                                <span class="badge px-3 py-2 fs-6 text-dark" style="background-color: var(--lavender-veil); border: 1px solid var(--lilac-ash);">
                                    <i class="bi bi-clock"></i> {{ $itinerario->durata_giorni }} Giorni
                                </span>
                            </div>

                            <!-- Pulsanti Azione Rapida -->
                            <div class="d-flex justify-content-end gap-2 mt-auto pt-2 border-top" style="border-color: var(--lilac-ash) !important;">
                                <a href="{{ route('itinerari.show', $itinerario->id) }}" class="btn btn-sm btn-outline-primary px-3" style="border-color: var(--lilac-ash); color: var(--text-dark);">
                                    <i class="bi bi-eye"></i> Dettagli
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert text-center py-4 rounded-3 m-0" style="background-color: #ffffff; border: 1px solid var(--lilac-ash); color: var(--text-dark);" role="alert">
                        Nessun itinerario trovato con i filtri selezionati. Prova a modificare i parametri!
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>