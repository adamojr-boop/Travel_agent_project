<?php

namespace Tests\Feature;

use App\Livewire\FornitoriIndex;
use App\Livewire\ItinerariEdit;
use App\Models\Conversation;
use App\Models\Fornitore;
use App\Models\Itinerario;
use App\Models\User;
use App\Support\AgentIdentity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.registration.invite_code' => 'codice-test']);
    }

    private function registration(string $name, string $invite = 'codice-test'): array
    {
        return ['name' => $name, 'password' => 'password-lunga-123', 'password_confirmation' => 'password-lunga-123', 'invite_code' => $invite];
    }

    public function test_pagine_di_accesso_e_registrazione_usano_il_layout_condiviso(): void
    {
        $this->get('/login')->assertOk()
            ->assertSee('data-bs-theme="light"', false)
            ->assertSee('Planner Helper')
            ->assertSee('name="name"', false)
            ->assertSee('name="password"', false)
            ->assertSee(route('register'));

        $this->get('/register')->assertOk()
            ->assertSee('data-bs-theme="light"', false)
            ->assertSee('Planner Helper')
            ->assertSee('name="password_confirmation"', false)
            ->assertSee('name="invite_code"', false)
            ->assertSee(route('login'));
    }

    public function test_accesso_e_registrazione_con_invito_e_dati_precedenti(): void
    {
        $chat = Conversation::create(['titolo' => 'Chat precedente']);
        $hotel = Fornitore::create(['nome' => 'Hotel precedente', 'ambito' => 'Alloggi']);
        $trip = Itinerario::create(['titolo' => 'Itinerario precedente']);
        $this->get('/itinerari')->assertRedirect(route('login'));
        $this->post('/register', $this->registration('primo', 'sbagliato'))->assertSessionHasErrors('invite_code');
        $this->assertDatabaseCount('users', 0);

        $this->post('/register', $this->registration('primo'))->assertRedirect(route('chat.index'));
        $user = User::where('name', 'primo')->firstOrFail();
        $this->assertTrue(Hash::check('password-lunga-123', $user->password));
        $this->assertSame($user->id, $chat->fresh()->user_id);
        $this->assertSame($user->id, $hotel->fresh()->user_id);
        $this->assertSame($user->id, $trip->fresh()->user_id);
        $this->post('/logout')->assertRedirect(route('login'));
        $this->assertGuest();
        $this->post('/login', ['name' => 'primo', 'password' => 'sbagliata'])->assertSessionHasErrors('name');
        $this->post('/login', ['name' => 'primo', 'password' => 'password-lunga-123'])->assertRedirect(route('chat.index'));
        $this->post('/logout');
        $this->post('/register', $this->registration('secondo'))->assertRedirect(route('chat.index'));
        $this->assertSame($user->id, $trip->fresh()->user_id);
    }

    public function test_dati_e_azioni_di_altri_utenti_non_sono_accessibili(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $chat = Conversation::create(['titolo' => 'Chat privata', 'user_id' => $owner->id]);
        $hotel = Fornitore::create(['nome' => 'Hotel privato', 'ambito' => 'Alloggi', 'user_id' => $owner->id]);
        $trip = Itinerario::create(['titolo' => 'Itinerario privato', 'user_id' => $owner->id]);
        $this->actingAs($other);
        $this->get('/chat/'.$chat->id)->assertNotFound();
        $this->delete('/chat/'.$chat->id)->assertNotFound();
        $this->patch('/chat/'.$chat->id.'/pin')->assertNotFound();
        $this->get('/itinerari')->assertOk()->assertDontSee('Itinerario privato')->assertDontSee('Chat privata');
        $this->get('/itinerari/'.$trip->id)->assertNotFound();
        $this->get('/itinerari/'.$trip->id.'/edit')->assertNotFound();
        $this->get('/itinerari/'.$trip->id.'/pdf')->assertNotFound();
        Livewire::test(FornitoriIndex::class)->assertDontSee('Hotel privato');
        $this->assertDatabaseHas('fornitori', ['id' => $hotel->id]);
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        Livewire::test(FornitoriIndex::class)->call('eliminaFornitore', $hotel->id);
    }

    public function test_api_richiede_identita_firmata_e_isola_i_dati(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $hotel = Fornitore::create(['nome' => 'Hotel privato', 'ambito' => 'Alloggi', 'user_id' => $owner->id]);
        $trip = Itinerario::create(['titolo' => 'Viaggio privato', 'user_id' => $owner->id]);
        $headers = ['X-Internal-Token' => 'test-token', 'X-Agent-Identity' => AgentIdentity::issue($other->id)];
        $this->getJson('/api/internal/fornitori', ['X-Internal-Token' => 'test-token'])->assertUnauthorized();
        $this->getJson('/api/internal/itinerari', array_merge($headers, ['X-Agent-Identity' => $owner->id.'.9999999999.invalid']))->assertUnauthorized();
        $this->getJson('/api/internal/fornitori', $headers)->assertOk()->assertJsonMissing(['id' => $hotel->id]);
        $this->getJson('/api/internal/itinerari', $headers)->assertOk()->assertJsonMissing(['id' => $trip->id]);
        $this->getJson('/api/internal/itinerari/'.$trip->id, $headers)->assertNotFound();
        $ownerHeaders = ['X-Internal-Token' => 'test-token', 'X-Agent-Identity' => AgentIdentity::issue($owner->id)];
        $this->getJson('/api/internal/itinerari/'.$trip->id, $ownerHeaders)->assertOk()->assertJson(['titolo' => 'Viaggio privato']);
        $this->postJson('/api/internal/itinerari', [
            'titolo' => 'Bozza', 'type' => 'trip',
            'giorni' => [['numero' => 1, 'servizi' => [['tipo' => 'Accommodation', 'titolo' => 'Hotel', 'fornitore' => 'Hotel privato']]]],
        ], $headers)->assertCreated();
        $draft = Itinerario::where('titolo', 'Bozza')->firstOrFail();
        $this->assertSame($other->id, $draft->user_id);
        $this->assertCount(0, $draft->fornitori);
    }

    public function test_modifica_non_puo_collegare_fornitore_di_un_altro_utente(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $hotel = Fornitore::create(['nome' => 'Hotel privato', 'ambito' => 'Alloggi', 'user_id' => $owner->id]);
        $trip = Itinerario::create(['titolo' => 'Viaggio', 'type' => 'trip', 'stato' => 'bozza', 'valuta' => 'USD', 'user_id' => $other->id]);
        $trip->giorni()->create(['numero' => 1]);
        $this->actingAs($other);
        Livewire::test(ItinerariEdit::class, ['id' => $trip->id])
            ->call('aggiungiServizio', 0)
            ->set('giorni.0.servizi.0.titolo', 'Hotel')
            ->set('giorni.0.servizi.0.fornitore_id', $hotel->id)
            ->call('save')
            ->assertHasErrors(['giorni.0.servizi.0.fornitore_id']);
        $this->assertCount(0, $trip->fornitori);
    }

    public function test_seeder_bloccato_dopo_assegnazione(): void
    {
        $user = User::factory()->create();
        \Illuminate\Support\Facades\DB::table('initial_data_owner')->where('id', 1)->update(['user_id' => $user->id]);
        $this->expectException(\RuntimeException::class);
        $this->seed(\Database\Seeders\FornitoreSeeder::class);
    }

    public function test_pagine_app_usano_il_tema_chiaro(): void
    {
        $this->actingAs(User::factory()->create());

        foreach (['/chat', '/itinerari', '/fornitori'] as $path) {
            $this->get($path)->assertOk()->assertSee('data-bs-theme="light"', false);
        }
    }
}
