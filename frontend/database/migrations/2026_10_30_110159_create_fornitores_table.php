<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fornitori', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('ambito'); // Es. Alloggi, Mezzi, Servizi
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fornitori');
    }
};