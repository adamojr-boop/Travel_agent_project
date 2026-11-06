<?php

namespace Tests\Feature;

use App\Livewire\ItinerariShow;
use App\Models\Itinerario;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ItinerariShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_azioni_del_dettaglio_restano_disponibili(): void
    {
        $user = User::factory()->create();
        $itinerario = Itinerario::create([
            'titolo' => 'Viaggio di prova',
            'type' => 'trip',
            'stato' => 'bozza',
            'user_id' => $user->id,
        ]);
        $this->actingAs($user);

        $this->get(route('itinerari.show', $itinerario->id))
            ->assertOk()
            ->assertSee(route('itinerari.pdf', $itinerario->id))
            ->assertSee(route('itinerari.edit', $itinerario->id))
            ->assertSee('Azioni itinerario')
            ->assertSee('Torna alla lista')
            ->assertSee('Sei sicura di voler eliminare questo itinerario?');

        Livewire::test(ItinerariShow::class, ['id' => $itinerario->id])
            ->assertSee('Segna come confermato')
            ->call('toggleStato')
            ->assertSee('Riporta a bozza')
            ->call('deleteItinerario')
            ->assertRedirect(route('itinerari.index'));

        $this->assertDatabaseMissing('itinerari', ['id' => $itinerario->id]);
    }
}
