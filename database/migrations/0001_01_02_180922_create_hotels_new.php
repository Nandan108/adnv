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
        Schema::create('hotels_new', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 50);
            $table->unsignedTinyInteger('etoiles');
            $table->enum('situation', ['Bord de mer', 'Montagne', 'Plage', 'Ville']);
            $table->string('ville_lieu', 100)->nullable()->comment('champ temporaire, à supprimer après correction id_lieu');
            $table->unsignedInteger('id_lieu');
            $table->string('adresse', 150);
            $table->string('postal_code', 9);
            $table->string('tel', 20);
            $table->string('mail', 50);
            $table->string('photo', 50);
            $table->tinyInteger('age_minimum');
            $table->enum('repas', ['Petit déjeuner', 'Demi-pension', 'All Inclusive', 'Ultra All Inclusive'])->default('Petit déjeuner');
            $table->string('slug', 100);
            $table->boolean('coup_coeur');
            $table->boolean('decouvrir')->default(0);

            $table->index('id_lieu', 'hotels_new_id_lieu_IDX');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels_new');
    }
};
