<?php

use App\Models\Fliere;
use App\Models\Module;
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
        Schema::create('module_flieres', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Fliere::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Module::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_flieres');
    }
};
