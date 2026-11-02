<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('itinerario_voci', function (Blueprint $table) {
        $table->id();
        $table->foreignId('itinerario_id')->constrained('itinerari')->onDelete('cascade');
        $table->foreignId('fornitore_id')->nullable()->constrained('fornitori')->onDelete('set null'); // Collegamento opzionale al fornitore
        $table->string('descrizione'); // Es. "Pernottamento Hotel 4 stelle" o "Transfer aeroporto"
        $table->decimal('prezzo_a_persona', 10, 2)->default(0); // Costo unitario a persona
        $table->integer('quantita_persone')->default(1); // Numero di persone a cui si applica
        $table->decimal('subtotal', 10, 2)->default(0); // Calcolato o salvato per comodità
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itinerario_voci');
    }
};
