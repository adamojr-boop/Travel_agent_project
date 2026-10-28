<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('itinerario_regione', function (Blueprint $table) {
            $table->id();
            $table->foreignId('itinerario_id')->constrained('itinerari')->onDelete('cascade');
            $table->foreignId('regione_id')->constrained('regioni')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('itinerario_regione');
    }
};