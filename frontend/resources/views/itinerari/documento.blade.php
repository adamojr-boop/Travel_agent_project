@php
    $fmt = fn ($d) => $d?->format('M, j Y');
    $valute = ['USD' => '$', 'EUR' => '€', 'GBP' => '£'];
    $sym = fn ($code) => $valute[$code ?? 'USD'] ?? ($code . ' ');
@endphp
<style>
    .doc { font-family: 'DejaVu Sans', sans-serif; color: #333; font-size: 12px; line-height: 1.5; }
    .doc h1 { color: #6b7a2a; font-size: 34px; margin: 0 0 4px; }
    .doc h2 { color: #6b7a2a; font-size: 20px; margin: 22px 0 8px; }
    .doc h3 { font-size: 15px; margin: 14px 0 4px; }
    .doc .sub { color: #888; font-size: 13px; margin-bottom: 10px; }
    .doc .banner { background: #6b7a2a; color: #fff; padding: 14px 18px; font-size: 16px; margin: 14px 0; }
    .doc table.grid { width: 100%; border-collapse: collapse; margin: 6px 0 12px; }
    .doc table.grid th, .doc table.grid td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; vertical-align: top; }
    .doc table.grid th { background: #f4f4ee; font-size: 11px; color: #666; }
    .doc ul { margin: 4px 0 8px 18px; padding: 0; }
    .doc .green { color: #6b7a2a; font-weight: bold; }
    .doc .muted { color: #888; }
    .doc .service { border-top: 1px solid #ddd; padding-top: 10px; margin-top: 12px; }
    .doc .type { color: #6b7a2a; font-weight: bold; font-size: 11px; text-transform: uppercase; }
    .doc .tag { background: #eef0e0; color: #555; padding: 1px 6px; font-size: 10px; }
    .doc .facts td { padding: 2px 14px 2px 0; }
    .doc .pb { page-break-before: always; }
</style>

<div class="doc">
    <h1>Italy</h1>
    <div class="sub">{{ $itinerario->titolo }}@if($itinerario->codice) · {{ $itinerario->codice }}@endif</div>
    <div class="banner">{{ $itinerario->type === 'wedding' ? 'Destination wedding in Italy' : 'Welcome to Italy' }}</div>

    <table class="grid">
        <tr>
            <th>Booking reference</th><th>Dates</th><th>Nights / Days</th><th>People</th>
        </tr>
        <tr>
            <td>{{ $itinerario->codice ?? '—' }}</td>
            <td>@if($itinerario->data_inizio){{ $fmt($itinerario->data_inizio) }} - {{ $fmt($itinerario->data_fine) }}@else — @endif</td>
            <td>{{ $itinerario->notti ?? '—' }} / {{ $itinerario->durata_giorni }}</td>
            <td>{{ $itinerario->persone ?? '—' }}</td>
        </tr>
    </table>

    <h2>Inclusions</h2>
    <ul>@foreach(config('itinerario.inclusions') as $t)<li>{{ $t }}</li>@endforeach</ul>

    <h2>Important notes</h2>
    <ul>@foreach(config('itinerario.important_notes') as $t)<li>{{ $t }}</li>@endforeach</ul>

    <h2>Italy facts</h2>
    <table class="facts">
        @foreach(config('itinerario.italy_facts') as $k => $v)
            <tr><td class="green">{{ $k }}:</td><td>{{ $v }}</td></tr>
        @endforeach
    </table>

    <h2 class="pb">Summary</h2>
    @foreach($itinerario->giorni as $giorno)
        <div style="margin-bottom: 10px;">
            <span class="green">Day {{ $giorno->numero }}</span>
            @if($giorno->data) <span class="muted">{{ $giorno->data->format('l') }} / {{ $giorno->data->format('M, j Y') }}</span>@endif
            @foreach($giorno->servizi as $s)
                <div>
                    <span class="muted">{{ $s->tipo }} ·</span>
                    @if($s->opzione)Option {{ $s->opzione }}) @endif
                    @if($s->opzionale)<span class="tag">Optional</span> @endif
                    {{ $s->titolo }}@if($s->tipo === 'Accommodation' && $s->notti) ({{ $s->notti }} {{ $s->notti === 1 ? 'night' : 'nights' }})@endif
                </div>
            @endforeach
        </div>
    @endforeach

    @foreach($itinerario->giorni as $giorno)
        <div class="pb">
            <h2>Day {{ $giorno->numero }}
                @if($giorno->data)<span class="muted" style="font-size: 13px;">{{ $giorno->data->format('l') }} / {{ $giorno->data->format('M, j') }}</span>@endif
                @if($giorno->citta)<br><span class="green" style="font-size: 16px;">{{ $giorno->citta }} welcomes you</span>@endif
            </h2>
            @if($giorno->intro)<p class="muted">{{ $giorno->intro }}</p>@endif

            @foreach($giorno->servizi as $s)
                <div class="service">
                    <div class="type">
                        {{ $s->tipo }}
                        @if($s->opzione) · Option {{ $s->opzione }}@endif
                        @if($s->opzionale) · <span class="tag">Optional</span>@endif
                    </div>
                    <h3>{{ $s->titolo }}</h3>

                    @php
                        $descrizione = $s->descrizione ?: ($s->tipo === 'Accommodation' ? $s->fornitore?->descrizione : null);
                    @endphp
                    @if($descrizione)<p>{{ $descrizione }}</p>@endif

                    @if($s->partecipanti || $s->durata || $s->notti)
                        <table class="grid" style="width: auto;">
                            <tr>
                                @if($s->partecipanti)<th>Participants</th>@endif
                                @if($s->durata)<th>Duration</th>@endif
                                @if($s->notti)<th>Nights</th>@endif
                            </tr>
                            <tr>
                                @if($s->partecipanti)<td>{{ $s->partecipanti }}</td>@endif
                                @if($s->durata)<td>{{ $s->durata }}</td>@endif
                                @if($s->notti)<td>{{ $s->notti }}</td>@endif
                            </tr>
                        </table>
                    @endif

                    @if(!empty($s->inclusioni))
                        <strong>Inclusions:</strong>
                        <ul>@foreach($s->inclusioni as $i)<li>{{ $i }}</li>@endforeach</ul>
                    @endif
                    @if(!empty($s->esclusioni))
                        <strong>Exclusions:</strong>
                        <ul>@foreach($s->esclusioni as $e)<li>{{ $e }}</li>@endforeach</ul>
                    @endif
                    @if($s->prezzo_a_persona !== null)
                        <p><strong>Cost</strong> {{ $sym($s->valuta) }}{{ number_format((float) $s->prezzo_a_persona, 0, '.', ',') }} per person</p>
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach

    <h2 class="pb">Contact us</h2>
    @if(config('itinerario.contact_email'))<p class="green">{{ config('itinerario.contact_email') }}</p>@endif
    <p>{{ config('itinerario.contact_text') }}</p>
    <p class="muted">Copyright © {{ now()->year }} All Rights Reserved.</p>
</div>
