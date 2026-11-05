<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('itinerari', function (Blueprint $table) {
            $table->string('codice')->nullable()->unique()->after('id');
            $table->date('data_inizio')->nullable();
            $table->date('data_fine')->nullable();
            $table->unsignedSmallInteger('notti')->nullable();
            $table->unsignedSmallInteger('persone')->nullable();
            $table->string('valuta', 3)->default('USD');
            $table->string('stato')->default('bozza');
        });

        Schema::table('fornitori', function (Blueprint $table) {
            $table->string('citta')->nullable();
            $table->text('descrizione')->nullable();
        });

        Schema::create('itinerario_giorni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('itinerario_id')->constrained('itinerari')->cascadeOnDelete();
            $table->unsignedSmallInteger('numero');
            $table->date('data')->nullable();
            $table->string('citta')->nullable();
            $table->text('intro')->nullable();
            $table->timestamps();
        });

        Schema::create('itinerario_servizi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('giorno_id')->constrained('itinerario_giorni')->cascadeOnDelete();
            $table->foreignId('fornitore_id')->nullable()->constrained('fornitori')->nullOnDelete();
            $table->unsignedSmallInteger('ordine')->default(1);
            $table->string('tipo');
            $table->string('titolo');
            $table->unsignedSmallInteger('opzione')->nullable();
            $table->boolean('opzionale')->default(false);
            $table->text('descrizione')->nullable();
            $table->unsignedSmallInteger('partecipanti')->nullable();
            $table->string('durata')->nullable();
            $table->unsignedSmallInteger('notti')->nullable();
            $table->json('inclusioni')->nullable();
            $table->json('esclusioni')->nullable();
            $table->decimal('prezzo_a_persona', 10, 2)->nullable();
            $table->string('valuta', 3)->nullable();
            $table->timestamps();
        });

        // Pulizia una tantum dei dati di prova: i dati reali arrivano dai seeder.
        DB::table('itinerario_voci')->delete();
        DB::table('itinerario_fornitore')->delete();
        DB::table('itinerari')->delete();
        DB::table('fornitori')->delete();
    }

    public function down(): void
    {
        Schema::dropIfExists('itinerario_servizi');
        Schema::dropIfExists('itinerario_giorni');

        Schema::table('fornitori', function (Blueprint $table) {
            $table->dropColumn(['citta', 'descrizione']);
        });

        Schema::table('itinerari', function (Blueprint $table) {
            $table->dropUnique(['codice']);
            $table->dropColumn(['codice', 'data_inizio', 'data_fine', 'notti', 'persone', 'valuta', 'stato']);
        });
    }
};
