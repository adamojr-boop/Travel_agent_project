<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
            $table->unique('name');
        });

        foreach (['conversations', 'itinerari', 'fornitori'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->constrained()->restrictOnDelete();
            });
        }

        Schema::create('initial_data_owner', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->primary();
            $table->foreignId('user_id')->nullable()->constrained()->restrictOnDelete();
        });
        DB::table('initial_data_owner')->insert(['id' => 1]);
    }

    public function down(): void
    {
        Schema::dropIfExists('initial_data_owner');
        foreach (['conversations', 'itinerari', 'fornitori'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropConstrainedForeignId('user_id');
            });
        }
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });
    }
};
