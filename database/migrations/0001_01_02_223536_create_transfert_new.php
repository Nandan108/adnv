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
        Schema::create('transfert_new', function (Blueprint $table) {
            $table->id();
            $table->string('titre', 150);
            $table->enum('type', ['car', 'hydravion', 'speedboat'])->default('car');
            $table->char('dpt_code_aeroport', 3)->comment('début du tranfert - code de l\'aéroport');
            $table->unsignedInteger('arv_id_hotel')->comment('arrivée du tranfert - id de l\'hotel');
            $table->unsignedInteger('id_partenaire')->nullable();
            $table->date('debut_validite');
            $table->date('fin_validite');
            $table->char('monnaie', 3);
            $table->float('taux_change')->unsigned();
            $table->unsignedSmallInteger('taux_commission')->nullable();
            $table->string('photo', 100);
            $table->string('service', 100);
            $table->unsignedSmallInteger('adulte_comm')->nullable();
            $table->unsignedSmallInteger('enfant_comm')->nullable();
            $table->unsignedSmallInteger('bebe_comm')->nullable();
            $table->unsignedTinyInteger('frais_priseencharge')->default(0);
            $table->decimal('adulte_a_net_1', 6, 2)->unsigned()->nullable();
            $table->decimal('adulte_a_brut_1', 6, 2)->unsigned()->nullable();
            $table->decimal('adulte_r_net_1', 6, 2)->unsigned()->nullable();
            $table->decimal('adulte_r_brut_1', 6, 2)->unsigned()->nullable();
            $table->decimal('adulte_total_1', 6, 2)->unsigned()->nullable();
            $table->decimal('adulte_a_net_2', 6, 2)->unsigned()->nullable();
            $table->decimal('adulte_a_brut_2', 6, 2)->unsigned()->nullable();
            $table->decimal('adulte_r_net_2', 6, 2)->unsigned()->nullable();
            $table->decimal('adulte_r_brut_2', 6, 2)->unsigned()->nullable();
            $table->decimal('adulte_total_2', 6, 2)->unsigned()->nullable();
            $table->decimal('adulte_a_net_3', 6, 2)->unsigned()->nullable();
            $table->decimal('adulte_a_brut_3', 6, 2)->unsigned()->nullable();
            $table->decimal('adulte_r_net_3', 6, 2)->unsigned()->nullable();
            $table->decimal('adulte_r_brut_3', 6, 2)->unsigned()->nullable();
            $table->decimal('adulte_total_3', 6, 2)->unsigned()->nullable();
            $table->decimal('adulte_a_net_4', 6, 2)->unsigned()->nullable();
            $table->decimal('adulte_a_brut_4', 6, 2)->unsigned()->nullable();
            $table->decimal('adulte_r_net_4', 6, 2)->unsigned()->nullable();
            $table->decimal('adulte_r_brut_4', 6, 2)->unsigned()->nullable();
            $table->decimal('adulte_total_4', 6, 2)->unsigned()->nullable();
            $table->decimal('enfant_a_net', 6, 2)->unsigned()->nullable();
            $table->decimal('enfant_a_brut', 6, 2)->unsigned()->nullable();
            $table->decimal('enfant_r_net', 6, 2)->unsigned()->nullable();
            $table->decimal('enfant_r_brut', 6, 2)->unsigned()->nullable();
            $table->decimal('enfant_total', 6, 2)->unsigned()->nullable();
            $table->decimal('bebe_a_net', 6, 2)->unsigned()->nullable();
            $table->decimal('bebe_a_brut', 6, 2)->unsigned()->nullable();
            $table->decimal('bebe_r_net', 6, 2)->unsigned()->nullable();
            $table->decimal('bebe_r_brut', 6, 2)->unsigned()->nullable();
            $table->decimal('bebe_total', 6, 2)->unsigned()->nullable();
            $table->decimal('handling_fees', 10, 2)->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfert_new');
    }
};
