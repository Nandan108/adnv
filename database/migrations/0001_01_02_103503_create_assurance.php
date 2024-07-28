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
        Schema::create('assurance', function (Blueprint $table) {
            $table->id();
            $table->string('titre_assurance', 50);
            $table->decimal('prix_assurance', 10, 2)->unsigned()->default(0.00);
            $table->text('prestation_assurance');
            $table->enum('couverture', ['par famille', 'par personne']);
            $table->enum('duree', ['annuelle', 'voyage']);
            $table->string('pourcentage', 20)->default('0');
            $table->decimal('prix_minimum', 5, 2)->unsigned()->nullable();
            $table->text('frais_annulation');
            $table->text('assistance');
            $table->text('fraisderecherche');
            $table->text('volretarde');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assurance');
    }
};
