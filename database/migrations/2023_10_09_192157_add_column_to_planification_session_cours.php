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
        Schema::table('planification_session_cours', function (Blueprint $table) {
            $table->time('heure_debut');
            $table->time('heure_fin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planification_session_cours', function (Blueprint $table) {
            //
        });
    }
};
