<?php

namespace Tests\Feature;

use App\Livewire\FornitoriIndex;
use App\Models\Fornitore;
use App\Models\Regione;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FornitoriIndexTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_filtri_ambito_regione_e_nome_si_combinano(): void
    {
        $lazio = Regione::create(['nome' => 'Lazio']);
        $veneto = Regione::create(['nome' => 'Veneto']);
        Fornitore::create(['nome' => 'Hotel Roma', 'ambito' => 'Alloggi', 'regione_id' => $lazio->id, 'user_id' => $this->user->id]);
        Fornitore::create(['nome' => 'Hotel Venezia', 'ambito' => 'Alloggi', 'regione_id' => $veneto->id, 'user_id' => $this->user->id]);
        Fornitore::create(['nome' => 'Roma Transfer', 'ambito' => 'Mezzi', 'regione_id' => $lazio->id, 'user_id' => $this->user->id]);
        Fornitore::create(['nome' => 'Roma Tour', 'ambito' => 'Servizi', 'regione_id' => $lazio->id, 'user_id' => $this->user->id]);
        Fornitore::create(['nome' => 'Hotel precedente', 'ambito' => 'Hotel', 'user_id' => $this->user->id]);

        Livewire::test(FornitoriIndex::class)
            ->assertSee('Hotel')
            ->assertSee('Transfers')
            ->assertSee('Tours')
            ->assertDontSee('setRegione')
            ->set('ambitoFilter', 'Alloggi')
            ->assertSee('Hotel precedente')
            ->assertDontSee('Roma Transfer')
            ->set('regioneFilter', (string) $lazio->id)
            ->set('search', 'Roma')
            ->assertSee('Hotel Roma')
            ->assertDontSee('Hotel Venezia')
            ->assertDontSee('Roma Transfer')
            ->assertDontSee('Roma Tour')
            ->set('ambitoFilter', 'Mezzi')
            ->assertSee('Roma Transfer')
            ->assertDontSee('Hotel Roma')
            ->set('ambitoFilter', 'Servizi')
            ->assertSee('Roma Tour')
            ->assertDontSee('Roma Transfer');
    }

    public function test_creazione_con_ambiti_predefiniti_e_regione(): void
    {
        $lazio = Regione::create(['nome' => 'Lazio']);

        foreach (Fornitore::AMBITI as $valore => $etichetta) {
            Livewire::test(FornitoriIndex::class)
                ->set('nuovoNome', 'Fornitore '.$etichetta)
                ->set('nuovoAmbito', $valore)
                ->set('nuovaRegione', (string) $lazio->id)
                ->call('salvaFornitore')
                ->assertHasNoErrors();

            $this->assertDatabaseHas('fornitori', [
                'nome' => 'Fornitore '.$etichetta,
                'ambito' => $valore,
                'regione_id' => $lazio->id,
            ]);
        }

        Livewire::test(FornitoriIndex::class)
            ->set('nuovoNome', 'Ambito non valido')
            ->set('nuovoAmbito', 'Altro')
            ->call('salvaFornitore')
            ->assertHasErrors(['nuovoAmbito']);
        $this->assertDatabaseMissing('fornitori', ['nome' => 'Ambito non valido']);

        Livewire::test(FornitoriIndex::class)
            ->set('nuovoNome', 'Ambito mancante')
            ->call('salvaFornitore')
            ->assertHasErrors(['nuovoAmbito']);
        $this->assertDatabaseMissing('fornitori', ['nome' => 'Ambito mancante']);
    }

    public function test_annullamento_ripulisce_form_ed_errori(): void
    {
        Livewire::test(FornitoriIndex::class)
            ->assertSee('Nuovo fornitore')
            ->call('toggleForm')
            ->assertSee('Aggiungi fornitore')
            ->assertSee('Ambito *')
            ->assertSee('Annulla')
            ->assertDontSee('Nuovo fornitore')
            ->set('nuovoNome', 'Da annullare')
            ->set('nuovoAmbito', 'Mezzi')
            ->set('nuovaCitta', 'Roma')
            ->set('nuovaRegione', '9999')
            ->set('nuovaDescrizione', 'Test')
            ->call('salvaFornitore')
            ->assertHasErrors(['nuovaRegione'])
            ->call('annullaInserimento')
            ->assertHasNoErrors()
            ->assertSet('mostraForm', false)
            ->assertSet('nuovoNome', '')
            ->assertSet('nuovoAmbito', '')
            ->assertSet('nuovaCitta', '')
            ->assertSet('nuovaRegione', '')
            ->assertSet('nuovaDescrizione', '')
            ->assertSee('Nuovo fornitore')
            ->call('toggleForm')
            ->set('nuovoNome', 'Da annullare')
            ->call('toggleForm')
            ->assertSet('mostraForm', false)
            ->assertSet('nuovoNome', '');

        $this->assertDatabaseMissing('fornitori', ['nome' => 'Da annullare']);
    }

    public function test_badge_ha_un_colore_per_ambito_e_un_fallback(): void
    {
        foreach (['Alloggi', 'Hotel', 'Mezzi', 'Servizi', 'Altro'] as $ambito) {
            Fornitore::create(['nome' => $ambito, 'ambito' => $ambito, 'user_id' => $this->user->id]);
        }

        $html = Livewire::test(FornitoriIndex::class)->html();
        $this->assertSame(2, substr_count($html, 'fornitore-ambito-hotel'));
        $this->assertSame(1, substr_count($html, 'fornitore-ambito-transfers'));
        $this->assertSame(1, substr_count($html, 'fornitore-ambito-tours'));
        $this->assertSame(1, substr_count($html, 'fornitore-ambito-altro'));
    }
}
