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
        Schema::create('taux_monnaie', function (Blueprint $table) {
            $table->id('id_taux_monnaie');
            $table->string('nom_monnaie', 30);
            $table->char('code', 3)->unique();
            $table->float('taux');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taux_monnaie');
    }
};
