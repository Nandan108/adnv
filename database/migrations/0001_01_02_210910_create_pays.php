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
        Schema::create('pays', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->char('code', 2);
            $table->string('nom_en_gb', 45);
            $table->string('nom_fr_fr', 45);
            $table->string('continent', 50)->nullable();
            $table->integer('ISO-3166-1-num')->nullable();
            $table->string('ISO-3166-1-a3', 3)->nullable();
            $table->unsignedTinyInteger('visa_enfant')->nullable()->comment('Prix CHF du visa obligatoire (frais commande inclus)');
            $table->unsignedTinyInteger('visa_adulte')->nullable()->comment('Prix CHF du visa obligatoire (frais commande inclus)');
            $table->unsignedTinyInteger('visa_bebe')->nullable()->comment('Prix CHF du visa obligatoire (frais commande inclus)');
            $table->boolean('obligatoire')->default(0);

            $table->unique('code', 'alpha2');
            $table->unique('ISO-3166-1-a3', 'alpha3');
            $table->unique('ISO-3166-1-num', 'code_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pays');
    }
};
