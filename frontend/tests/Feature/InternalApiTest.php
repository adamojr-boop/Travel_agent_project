<?php

namespace Tests\Feature;

use App\Models\Fornitore;
use App\Models\Itinerario;
use App\Models\Regione;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InternalApiTest extends TestCase
{
    use RefreshDatabase;

    private array $headers = ['X-Internal-Token' => 'test-token'];

    private function bozza(array $extra = []): array
    {
        return array_merge([
            'titolo' => 'Rome test (10 pax)',
            'type' => 'trip',
            'persone' => 10,
            'giorni' => [[
                'numero' => 1,
                'citta' => 'Rome',
                'servizi' => [[
                    'tipo' => 'Accommodation',
                    'titolo' => 'Hotel Roma',
                    'fornitore' => 'Hotel Roma',
                ]],
            ]],
        ], $extra);
    }

    public function test_richiede_il_token(): void
    {
        $this->getJson('/api/internal/itinerari')->assertStatus(401);
        $this->getJson('/api/internal/fornitori', ['X-Internal-Token' => 'sbagliato'])->assertStatus(401);
        $this->postJson('/api/internal/itinerari', $this->bozza())->assertStatus(401);
    }

    public function test_store_salva_sempre_come_bozza_con_regione_e_fornitore(): void
    {
        $fornitore = Fornitore::create(['nome' => 'Hotel Roma', 'ambito' => 'Alloggi']);

        $this->postJson('/api/internal/itinerari', $this->bozza(['stato' => 'confermato']), $this->headers)
            ->assertCreated()
            ->assertJson(['stato' => 'bozza']);

        $it = Itinerario::where('titolo', 'Rome test (10 pax)')->firstOrFail();
        $this->assertSame('bozza', $it->stato);
        $this->assertSame(1, $it->giorni()->count());
        $this->assertTrue($it->fornitori->contains($fornitore));
        $this->assertContains('Lazio', $it->regioni->pluck('nome')->all());
    }

    public function test_store_rifiuta_dati_non_validi(): void
    {
        $this->postJson('/api/internal/itinerari', $this->bozza(['type' => 'altro']), $this->headers)->assertStatus(422);

        $dati = $this->bozza();
        $dati['giorni'][0]['servizi'][0]['tipo'] = 'Inventato';
        $this->postJson('/api/internal/itinerari', $dati, $this->headers)->assertStatus(422);
    }

    public function test_filtri_itinerari(): void
    {
        Fornitore::create(['nome' => 'Hotel Roma', 'ambito' => 'Alloggi']);
        $this->postJson('/api/internal/itinerari', $this->bozza(), $this->headers)->assertCreated();
        $this->postJson('/api/internal/itinerari', $this->bozza([
            'titolo' => 'Wedding Venice', 'type' => 'wedding',
            'giorni' => [['numero' => 1, 'citta' => 'Venice', 'servizi' => []]],
        ]), $this->headers)->assertCreated();

        $titoli = fn (string $q) => collect($this->getJson("/api/internal/itinerari?$q", $this->headers)->assertOk()->json())->pluck('titolo')->all();

        $this->assertCount(2, $titoli(''));
        $this->assertSame(['Wedding Venice'], $titoli('tipo=wedding'));
        $this->assertSame(['Rome test (10 pax)'], $titoli('regione=Lazio'));
        $this->assertSame(['Rome test (10 pax)'], $titoli('fornitore=Hotel Roma'));
        $this->assertSame([], $titoli('regione=Sardegna'));
    }

    public function test_filtro_fornitori_per_regione(): void
    {
        $lazio = Regione::firstOrCreate(['nome' => 'Lazio']);
        Fornitore::create(['nome' => 'A', 'ambito' => 'Alloggi', 'regione_id' => $lazio->id]);
        Fornitore::create(['nome' => 'B', 'ambito' => 'Alloggi']);

        $nomi = collect($this->getJson('/api/internal/fornitori?regione=Lazio', $this->headers)->assertOk()->json())->pluck('nome')->all();
        $this->assertSame(['A'], $nomi);
    }
}
