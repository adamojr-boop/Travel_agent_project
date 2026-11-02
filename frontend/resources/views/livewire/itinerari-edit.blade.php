<div class="container py-4">
    <div class="card ragsy-card p-4 shadow-sm">
        
        <!-- Intestazione -->
        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom" style="border-color: var(--lilac-ash) !important;">
            <div>
                <h2 class="mb-1 fw-bold" style="color: var(--text-dark);">Modifica Itinerario</h2>
                <p class="mb-0 text-muted">Aggiorna i dettagli, le regioni e i fornitori associati a questo viaggio.</p>
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

        <!-- Form di Modifica -->
        <form wire:submit.prevent="update">
            
            <div class="row g-3 mb-4">
                <!-- Titolo -->
                <div class="col-md-12">
                    <label for="titolo" class="form-label fw-bold" style="color: var(--text-dark);">Titolo dell'Itinerario</label>
                    <input wire:model="titolo" type="text" id="titolo" class="form-control" style="background-color: #ffffff; border-color: var(--lilac-ash); color: var(--text-dark);">
                    @error('titolo') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <!-- Descrizione -->
                <div class="col-md-12">
                    <label for="descrizione" class="form-label fw-bold" style="color: var(--text-dark);">Descrizione</label>
                    <textarea wire:model="descrizione" id="descrizione" rows="4" class="form-control" style="background-color: #ffffff; border-color: var(--lilac-ash); color: var(--text-dark);"></textarea>
                    @error('descrizione') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <!-- Prezzo -->
                <div class="col-md-6">
                    <label for="prezzo" class="form-label fw-bold" style="color: var(--text-dark);">Prezzo Totale (€)</label>
                    <input wire:model="prezzo" type="number" step="0.01" id="prezzo" class="form-control" style="background-color: #ffffff; border-color: var(--lilac-ash); color: var(--text-dark);">
                    @error('prezzo') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <!-- Durata Giorni -->
                <div class="col-md-6">
                    <label for="durata_giorni" class="form-label fw-bold" style="color: var(--text-dark);">Durata (Giorni)</label>
                    <input wire:model="durata_giorni" type="number" id="durata_giorni" class="form-control" style="background-color: #ffffff; border-color: var(--lilac-ash); color: var(--text-dark);">
                    @error('durata_giorni') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row g-4 mb-4">
                <!-- Sezione Regioni (Checkbox multiple) -->
                <div class="col-md-6">
                    <div class="p-3 rounded-3" style="background-color: var(--lavender-veil); border: 1px solid var(--lilac-ash);">
                        <label class="form-label fw-bold mb-3" style="color: var(--text-dark);"><i class="bi bi-geo-alt"></i> Regioni Coinvolte</label>
                        <div class="d-flex flex-column gap-2">
                            @foreach($tutteLeRegioni as $regione)
                                <div class="form-check">
                                    <input wire:model="selectedRegioni" value="{{ $regione->id }}" type="checkbox" class="form-check-input" id="regione-{{ $regione->id }}">
                                    <label class="form-check-label" for="regione-{{ $regione->id }}" style="color: var(--text-dark);">
                                        {{ $regione->nome }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Sezione Fornitori (Checkbox multiple) -->
                <div class="col-md-6">
                    <div class="p-3 rounded-3" style="background-color: var(--lavender-veil); border: 1px solid var(--lilac-ash);">
                        <label class="form-label fw-bold mb-3" style="color: var(--text-dark);"><i class="bi bi-building"></i> Fornitori Partner</label>
                        <div class="d-flex flex-column gap-2" style="max-height: 200px; overflow-y: auto;">
                            @foreach($tuttiIFornitori as $fornitore)
                                <div class="form-check">
                                    <input wire:model="selectedFornitori" value="{{ $fornitore->id }}" type="checkbox" class="form-check-input" id="fornitore-{{ $fornitore->id }}">
                                    <label class="form-check-label" for="fornitore-{{ $fornitore->id }}" style="color: var(--text-dark);">
                                        {{ $fornitore->nome }} <span class="text-muted small">({{ $fornitore->ambito }})</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pulsante di Salvataggio -->
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('itinerari.index') }}" class="btn btn-light px-4" style="border: 1px solid var(--lilac-ash);">Annulla</a>
                <button type="submit" class="btn px-4 text-white fw-bold" style="background-color: var(--text-dark);">
                    <i class="bi bi-check-lg"></i> Salva Modifiche
                </button>
            </div>

        </form>
    </div>
</div>