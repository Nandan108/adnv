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
        Schema::create('partenaire', function (Blueprint $table) {
            $table->id('id_partenaire');
            $table->string('nom_partenaire', 50);
            $table->unsignedInteger('id_lieu')->default(0);
            $table->string('adresse', 100);
            $table->string('codepostal', 10);
            $table->string('telephone_hotel', 20);
            $table->string('adresse_reservation', 50);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partenaire');
    }
};
