<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fornitori', function (Blueprint $table) {
            $table->foreignId('regione_id')->nullable()->after('citta')->constrained('regioni')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('fornitori', function (Blueprint $table) {
            $table->dropConstrainedForeignId('regione_id');
        });
    }
};
