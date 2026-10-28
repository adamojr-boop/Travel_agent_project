<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-12 text-center">
            <h1 class="fw-bold text-primary">ZampApp - Esplora gli Itinerari</h1>
            <p class="text-muted">Trova il viaggio perfetto tra le meraviglie d'Italia</p>
        </div>
    </div>

    <!-- SEZIONE FILTRI -->
    <div class="card shadow-sm mb-4 p-4 bg-light border-0">
        <div class="row g-3">
            <!-- Filtro per Regione -->
            <div class="col-md-4">
                <label for="searchRegione" class="form-label fw-semibold">Filtra per Regione</label>
                <select wire:model.live="searchRegione" id="searchRegione" class="form-select">
                    <option value="">Tutte le regioni</option>
                    @foreach($regioni as $regione)
                        <option value="{{ $regione->nome }}">{{ $regione->nome }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filtro per Prezzo Massimo -->
            <div class="col-md-4">
                <label for="prezzoMax" class="form-label fw-semibold">Prezzo Massimo (€)</label>
                <input wire:model.live="prezzoMax" type="number" id="prezzoMax" class="form-control" placeholder="Es. 600">
            </div>

            <!-- Filtro per Durata Minima -->
            <div class="col-md-4">
                <label for="durataMinima" class="form-label fw-semibold">Durata Minima (Giorni)</label>
                <input wire:model.live="durataMinima" type="number" id="durataMinima" class="form-control" placeholder="Es. 3">
            </div>
        </div>
    </div>

    <!-- GRIGLIA DEGLI ITINERARI -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @forelse($itinerari as $itinerario)
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold text-dark">{{ $itinerario->titolo }}</h5>
                        <p class="card-text text-secondary small flex-grow-1">{{ $itinerario->descrizione }}</p>
                        
                        <!-- Badge delle Regioni collegate -->
                        <div class="mb-3">
                            @foreach($itinerario->regioni as $reg)
                                <span class="badge bg-secondary me-1">{{ $reg->nome }}</span>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top">
                            <span class="text-success fw-bold fs-5">€ {{ number_format($itinerario->prezzo, 2, ',', '.') }}</span>
                            <span class="badge bg-primary text-light">{{ $itinerario->durata_giorni }} Giorni</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning text-center py-4" role="alert">
                    Nessun itinerario trovato con i filtri selezionati. Prova a modificare i parametri di ricerca!
                </div>
            </div>
        @endforelse
    </div>
</div>