<div class="container py-4">
    <div class="card ragsy-card p-4 shadow-sm">

        <div class="d-flex justify-content-between align-items-start mb-4 pb-3 border-bottom flex-wrap gap-2" style="border-color: var(--lilac-ash) !important;">
            <div class="d-flex gap-2 flex-wrap">
                <span class="badge px-3 py-2 text-white fw-bold" style="background-color: var(--text-dark);">
                    <i class="bi bi-clock"></i> {{ $itinerario->durata_giorni }} Giorni
                </span>
                <span class="badge px-3 py-2 text-white fw-bold {{ $itinerario->type === 'wedding' ? 'bg-danger' : 'bg-primary' }}">
                    {{ $itinerario->type === 'wedding' ? '💍 Wedding' : '✈️ Trip' }}
                </span>
                <span class="badge px-3 py-2 fw-bold {{ $itinerario->stato === 'bozza' ? 'bg-warning text-dark' : 'bg-success text-white' }}">
                    {{ $itinerario->stato === 'bozza' ? '📝 Bozza' : '✅ Confermato' }}
                </span>
                @foreach($itinerario->regioni as $r)
                    <span class="badge px-3 py-2 text-dark" style="background-color: #ffffff; border: 1px solid var(--lilac-ash);">{{ $r->nome }}</span>
                @endforeach
            </div>
            <a href="{{ route('itinerari.index') }}" class="btn btn-light itinerary-detail-button btn-sm" aria-label="Torna alla lista" title="Torna alla lista">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
            </a>
        </div>

        @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Anteprima del documento (stessa vista usata per il PDF) -->
        <div class="mb-4 p-4 rounded-3" style="background-color: #ffffff; border: 1px solid var(--lilac-ash); color: #333;">
            @include('itinerari.documento', ['itinerario' => $itinerario])
        </div>

        <div class="d-flex justify-content-between align-items-center pt-3 border-top flex-wrap gap-2" style="border-color: var(--lilac-ash) !important;">
            <a href="{{ route('itinerari.index') }}" class="btn btn-light itinerary-detail-button" aria-label="Torna alla lista" title="Torna alla lista">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
            </a>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('itinerari.pdf', $itinerario->id) }}" class="btn px-4 text-white fw-bold shadow-sm" style="background-color: #6c5ce7;">
                    <i class="bi bi-file-earmark-pdf"></i> Scarica PDF
                </a>
                <div class="dropup">
                    <button type="button" class="btn btn-light itinerary-detail-button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Azioni itinerario" title="Azioni itinerario">
                        <i class="bi bi-three-dots-vertical" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <button type="button" wire:click="toggleStato" class="dropdown-item">
                                <i class="bi bi-arrow-repeat me-2" aria-hidden="true"></i>{{ $itinerario->stato === 'bozza' ? 'Segna come confermato' : 'Riporta a bozza' }}
                            </button>
                        </li>
                        <li>
                            <a href="{{ route('itinerari.edit', $itinerario->id) }}" class="dropdown-item">
                                <i class="bi bi-pencil-square me-2" aria-hidden="true"></i>Modifica
                            </a>
                        </li>
                        <li>
                            <button type="button" wire:click="deleteItinerario" wire:confirm="Sei sicura di voler eliminare questo itinerario?" class="dropdown-item text-danger">
                                <i class="bi bi-trash me-2" aria-hidden="true"></i>Elimina
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
