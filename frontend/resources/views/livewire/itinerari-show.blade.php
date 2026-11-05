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
                    <span class="badge px-3 py-2 text-dark" style="background-color: var(--lavender-veil); border: 1px solid var(--lilac-ash);">{{ $r->nome }}</span>
                @endforeach
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

        <!-- Anteprima del documento (stessa vista usata per il PDF) -->
        <div class="mb-4 p-4 rounded-3" style="background-color: #ffffff; border: 1px solid var(--lilac-ash); color: #333;">
            @include('itinerari.documento', ['itinerario' => $itinerario])
        </div>

        <div class="d-flex justify-content-between align-items-center pt-3 border-top flex-wrap gap-2" style="border-color: var(--lilac-ash) !important;">
            <a href="{{ route('itinerari.index') }}" class="btn btn-light px-4" style="border: 1px solid var(--lilac-ash);">Indietro</a>
            <div class="d-flex gap-2 flex-wrap">
                <button wire:click="toggleStato" class="btn btn-outline-secondary px-4">
                    {{ $itinerario->stato === 'bozza' ? 'Segna come confermato' : 'Riporta a bozza' }}
                </button>
                <a href="{{ route('itinerari.pdf', $itinerario->id) }}" class="btn px-4 text-white fw-bold shadow-sm" style="background-color: #6c5ce7;">
                    <i class="bi bi-file-earmark-pdf"></i> Scarica PDF
                </a>
                <a href="{{ route('itinerari.edit', $itinerario->id) }}" class="btn px-4 text-white fw-bold" style="background-color: var(--text-dark);">
                    <i class="bi bi-pencil-square"></i> Modifica
                </a>
                <button wire:click="deleteItinerario" wire:confirm="Sei sicura di voler eliminare questo itinerario?" class="btn btn-outline-danger px-4">
                    <i class="bi bi-trash"></i> Elimina
                </button>
            </div>
        </div>
    </div>
</div>
