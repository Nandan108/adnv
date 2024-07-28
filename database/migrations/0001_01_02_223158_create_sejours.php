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
        Schema::create('sejours', function (Blueprint $table) {
            $table->id();
            $table->string('titre', 250);
            $table->unsignedInteger('id_vol');
            $table->unsignedInteger('id_transfert');
            $table->unsignedInteger('id_chambre');
            $table->date('debut_vente');
            $table->date('fin_vente');
            $table->date('debut_voyage');
            $table->date('fin_voyage');
            $table->tinyInteger('nb_nuit')->default(1);
            $table->boolean('promo')->default(0);
            $table->boolean('avant')->default(0);
            $table->boolean('coup_coeur')->default(0);
            $table->boolean('manuel')->default(0);
            $table->string('photo', 100)->default('');
            $table->text('inclu')->default('');
            $table->text('noninclu')->default('');
            $table->decimal('simple_adulte_1', 6, 0)->nullable();
            $table->decimal('double_enfant_1a', 6, 0)->nullable();
            $table->decimal('double_enfant_1b', 6, 0)->nullable();
            $table->decimal('simple_enfant_1a', 6, 0)->nullable();
            $table->decimal('simple_enfant_1b', 6, 0)->nullable();
            $table->decimal('simple_enfant_2', 6, 0)->nullable();
            $table->decimal('simple_bebe', 6, 0)->nullable();
            $table->decimal('double_adulte_1', 6, 0)->nullable();
            $table->decimal('double_adulte_2', 6, 0)->nullable();
            $table->decimal('double_enfant_2', 6, 0)->nullable();
            $table->decimal('double_bebe', 6, 0)->nullable();
            $table->decimal('triple_adulte_1', 6, 0)->nullable();
            $table->decimal('triple_adulte_2', 6, 0)->nullable();
            $table->decimal('triple_adulte_3', 6, 0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sejours');
    }
};
