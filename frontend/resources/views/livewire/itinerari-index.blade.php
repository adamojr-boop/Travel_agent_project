<div class="d-flex flex-column gap-4">
    <div class="card ragsy-card p-4 d-flex flex-column gap-4">
        
        <!-- Intestazione della sezione -->
        <div>
            <h2 class="mb-1" style="color: var(--text-dark);">Esplora gli Itinerari</h2>
            <p class="mb-0" style="color: var(--text-dark);">Trova il viaggio perfetto filtrando per regione, prezzo o durata.</p>
        </div>

        <!-- Sezione Filtri con sfondo lavanda/azzurro -->
        <div class="filters-panel p-4">
            <div class="row g-3">
                <!-- Filtro per Regione -->
                <div class="col-md-4">
                    <label for="searchRegione" class="form-label fw-bold" style="color: var(--text-dark);">Filtra per Regione</label>
                    <select wire:model.live="searchRegione" id="searchRegione" class="form-select" style="background-color: #ffffff; border-color: var(--lilac-ash); color: var(--text-dark);">
                        <option value="">Tutte le regioni</option>
                        @foreach($regioni as $regione)
                            <option value="{{ $regione->nome }}">{{ $regione->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro per Prezzo Massimo -->
                <div class="col-md-4">
                    <label for="prezzoMax" class="form-label fw-bold" style="color: var(--text-dark);">Prezzo Massimo (€)</label>
                    <input wire:model.live="prezzoMax" type="number" id="prezzoMax" class="form-control" placeholder="Es. 600" style="background-color: #ffffff; border-color: var(--lilac-ash); color: var(--text-dark);">
                </div>

                <!-- Filtro per Durata Minima -->
                <div class="col-md-4">
                    <label for="durataMinima" class="form-label fw-bold" style="color: var(--text-dark);">Durata Minima (Giorni)</label>
                    <input wire:model.live="durataMinima" type="number" id="durataMinima" class="form-control" placeholder="Es. 3" style="background-color: #ffffff; border-color: var(--lilac-ash); color: var(--text-dark);">
                </div>
            </div>
        </div>
    </div>

    <div class="card ragsy-card p-4">
        <!-- Griglia degli Itinerari con sfondo lavanda/azzurro -->
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @forelse($itinerari as $itinerario)
                <div class="col">
                    <div class="card h-100 shadow-sm rounded-3 itinerary-card">
                        <div class="card-body d-flex flex-column p-4">
                            <!-- Titolo del viaggio -->
                            <h4 class="card-title fw-bold mb-2" style="color: var(--text-dark);">{{ $itinerario->titolo }}</h4>
                            
                            <!-- Descrizione -->
                            <p class="card-text small flex-grow-1 mb-3" style="color: var(--text-dark);">
                                {{ $itinerario->descrizione }}
                            </p>
                            
                            <!-- Badge delle Regioni collegate -->
                            <div class="mb-3">
                                @foreach($itinerario->regioni as $reg)
                                    <span class="badge me-1 px-2 py-1" style="background-color: #ffffff; color: var(--text-dark); border: 1px solid var(--lilac-ash);">
                                        {{ $reg->nome }}
                                    </span>
                                @endforeach
                            </div>

                            <!-- Prezzo e Durata -->
                            <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top" style="border-color: var(--lilac-ash);">
                                <span class="fw-bold fs-4" style="color: #198754;">€ {{ number_format($itinerario->prezzo, 2, ',', '.') }}</span>
                                <span class="badge px-3 py-2 fs-6" style="background-color: #ffffff; color: var(--text-dark); border: 1px solid var(--lilac-ash);">
                                    {{ $itinerario->durata_giorni }} Giorni
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert text-center py-4 rounded-3 m-0" style="background-color: #ffffff; border: 1px solid var(--border-color); color: var(--text-dark);" role="alert">
                        Nessun itinerario trovato con i filtri selezionati. Prova a modificare i parametri!
                    </div>
                </div>
            @endforelse
        </div>

    </div>
</div>