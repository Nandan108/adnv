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
        Schema::create('prestations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('provider_id');
            $table->enum('provider_type', ['App\\Models\\Hotel', 'App\\Models\\Circuit', 'App\\Models\\Croisiere']);
            $table->unsignedInteger('id_type')->nullable()->comment('refers to tbloption');
            $table->string('name', 100)->nullable();
            $table->mediumText('description')->nullable();
            $table->boolean('obligatoire')->unsigned()->default(0);
            $table->date('debut_validite');
            $table->date('fin_validite');
            $table->char('code_monnaie', 3);
            $table->unsignedTinyInteger('taux_commission')->default(0);
            $table->decimal('adulte_net', 6, 2)->unsigned()->nullable();
            $table->decimal('enfant_net', 6, 2)->unsigned()->nullable();
            $table->decimal('bebe_net', 6, 2)->unsigned()->nullable();
            $table->string('photo', 50)->nullable();
            $table->timestamps();

            $table->index(['provider_type', 'provider_id'], 'owner_type_owner_id');
            $table->index('id_type', 'id_partenaire');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestations');
    }
};
