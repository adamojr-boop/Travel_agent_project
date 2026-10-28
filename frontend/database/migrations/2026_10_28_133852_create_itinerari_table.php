<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('itinerari', function (Blueprint $table) {
        $table->id();
        $table->string('titolo');
        $table->text('descrizione')->nullable();
        $table->decimal('prezzo', 8, 2)->default(0);
        $table->integer('durata_giorni')->default(1);
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('itinerari');
    }
};