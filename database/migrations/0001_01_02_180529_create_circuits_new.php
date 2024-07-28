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
        Schema::create('circuits_new', function (Blueprint $table) {
            $table->id();
            $table->string('titre', 50);
            $table->string('tour_code', 20)->nullable();
            $table->unsignedInteger('id_lieu_dpt')->nullable();
            $table->unsignedInteger('id_lieu_arr')->nullable();
            $table->set('jours_depart', ['1','2','3','4','5','6','7'])->default('1,2,3,4,5,6,7');
            $table->longText('dates_depart')->nullable();
            $table->unsignedTinyInteger('nb_nuit')->default(1);
            $table->enum('type_repas', ['Selon programme','Sans repas','Petit déjeuner'])->default('Selon programme');
            $table->enum('type_circuit', ['Privé', 'Collectif']);
            $table->enum('langue', ['Francophone', 'Anglophone'])->default('Francophone');
            $table->date('debut_validite');
            $table->date('fin_validite');
            $table->string('photo', 40)->nullable();
            $table->unsignedTinyInteger('remise_pct')->default(0);
            $table->date('remise_debut')->nullable();
            $table->date('remise_fin')->nullable();
            $table->date('remise_debut_voyage')->nullable();
            $table->date('remise_fin_voyage')->nullable();
            $table->string('remise_code_promo', 50)->default('');
            $table->text('inclus')->nullable();
            $table->text('non_inclus')->nullable();
            $table->unsignedBigInteger('id_vol')->default(0);
            $table->string('slug', 100)->default('0');
            $table->char('monnaie', 3)->nullable();
            $table->float('taux_change')->default(0);
            $table->unsignedTinyInteger('taux_commission')->default(0);
            $table->unsignedTinyInteger('simple_nb_max')->default(0);
            $table->unsignedTinyInteger('simple_adulte_max')->default(0);
            $table->unsignedTinyInteger('simple_enfant_max')->default(0);
            $table->unsignedTinyInteger('simple_bebe_max')->default(0);
            $table->decimal('simple_adulte_1_net_a', 8, 2)->nullable();
            $table->decimal('simple_adulte_1_net_b', 8, 2)->nullable();
            $table->decimal('simple_adulte_1_brut', 8, 2)->nullable();
            $table->decimal('simple_adulte_1_total', 8, 2)->nullable();
            $table->unsignedTinyInteger('simple_enfant_1_agemin')->default(0);
            $table->unsignedTinyInteger('simple_enfant_1_agemax')->default(0);
            $table->decimal('simple_enfant_1_net', 8, 2)->nullable();
            $table->decimal('simple_enfant_1_brut', 8, 2)->nullable();
            $table->decimal('simple_enfant_1_total', 8, 2)->nullable();
            $table->unsignedTinyInteger('simple_enfant_2_agemin')->default(0);
            $table->unsignedTinyInteger('simple_enfant_2_agemax')->default(0);
            $table->decimal('simple_enfant_2_net', 8, 2)->nullable();
            $table->decimal('simple_enfant_2_brut', 8, 2)->nullable();
            $table->decimal('simple_enfant_2_total', 8, 2)->nullable();
            $table->unsignedTinyInteger('simple_enfant_3_agemin')->default(0);
            $table->unsignedTinyInteger('simple_enfant_3_agemax')->default(0);
            $table->decimal('simple_enfant_3_net', 8, 2)->nullable();
            $table->decimal('simple_enfant_3_brut', 8, 2)->nullable();
            $table->decimal('simple_enfant_3_total', 8, 2)->nullable();
            $table->unsignedTinyInteger('simple_bebe_1_agemax')->nullable();
            $table->decimal('simple_bebe_1_net', 8, 2)->nullable();
            $table->decimal('simple_bebe_1_brut', 8, 2)->nullable();
            $table->decimal('simple_bebe_1_total', 8, 2)->nullable();
            $table->unsignedTinyInteger('double_nb_max')->default(0);
            $table->unsignedTinyInteger('double_adulte_max')->default(0);
            $table->unsignedTinyInteger('double_enfant_max')->default(0);
            $table->unsignedTinyInteger('double_bebe_max')->default(0);
            $table->decimal('double_adulte_1_net', 8, 2)->nullable();
            $table->decimal('double_adulte_1_brut', 8, 2)->nullable();
            $table->decimal('double_adulte_1_total', 8, 2)->nullable();
            $table->decimal('double_adulte_2_net', 8, 2)->nullable();
            $table->decimal('double_adulte_2_brut', 8, 2)->nullable();
            $table->decimal('double_adulte_2_total', 8, 2)->nullable();
            $table->unsignedTinyInteger('double_enfant_1_agemin')->default(0);
            $table->unsignedTinyInteger('double_enfant_1_agemax')->default(0);
            $table->decimal('double_enfant_1_net', 8, 2)->nullable();
            $table->decimal('double_enfant_1_brut', 8, 2)->nullable();
            $table->decimal('double_enfant_1_total', 8, 2)->nullable();
            $table->unsignedTinyInteger('double_enfant_2_agemin')->default(0);
            $table->unsignedTinyInteger('double_enfant_2_agemax')->default(0);
            $table->decimal('double_enfant_2_net', 8, 2)->nullable();
            $table->decimal('double_enfant_2_brut', 8, 2)->nullable();
            $table->decimal('double_enfant_2_total', 8, 2)->nullable();
            $table->unsignedTinyInteger('double_enfant_3_agemin')->default(0);
            $table->unsignedTinyInteger('double_enfant_3_agemax')->default(0);
            $table->decimal('double_enfant_3_net', 8, 2)->nullable();
            $table->decimal('double_enfant_3_brut', 8, 2)->nullable();
            $table->decimal('double_enfant_3_total', 8, 2)->nullable();
            $table->unsignedTinyInteger('double_bebe_1_agemax')->nullable();
            $table->decimal('double_bebe_1_net', 8, 2)->nullable();
            $table->decimal('double_bebe_1_brut', 8, 2)->nullable();
            $table->decimal('double_bebe_1_total', 8, 2)->nullable();
            $table->unsignedTinyInteger('tripple_nb_max')->default(0);
            $table->unsignedTinyInteger('tripple_enfant_max')->default(0);
            $table->unsignedTinyInteger('tripple_adulte_max')->default(0);
            $table->decimal('tripple_adulte_1_net', 8, 2)->nullable();
            $table->decimal('tripple_adulte_1_brut', 8, 2)->nullable();
            $table->decimal('tripple_adulte_1_total', 8, 2)->nullable();
            $table->decimal('tripple_adulte_2_net', 8, 2)->nullable();
            $table->decimal('tripple_adulte_2_brut', 8, 2)->nullable();
            $table->decimal('tripple_adulte_2_total', 8, 2)->nullable();
            $table->decimal('tripple_adulte_3_net', 8, 2)->nullable();
            $table->decimal('tripple_adulte_3_brut', 8, 2)->nullable();
            $table->decimal('tripple_adulte_3_total', 8, 2)->nullable();
            $table->unsignedTinyInteger('quatre_nb_max')->default(0);
            $table->unsignedTinyInteger('quatre_adulte_max')->default(0);
            $table->decimal('quatre_adulte_1_net', 8, 2)->nullable();
            $table->decimal('quatre_adulte_1_brut', 8, 2)->nullable();
            $table->decimal('quatre_adulte_1_total', 8, 2)->nullable();
            $table->decimal('quatre_adulte_2_net', 8, 2)->nullable();
            $table->decimal('quatre_adulte_2_brut', 8, 2)->nullable();
            $table->decimal('quatre_adulte_2_total', 8, 2)->nullable();
            $table->decimal('quatre_adulte_3_net', 8, 2)->nullable();
            $table->decimal('quatre_adulte_3_brut', 8, 2)->nullable();
            $table->decimal('quatre_adulte_3_total', 8, 2)->nullable();
            $table->decimal('quatre_adulte_4_net', 8, 2)->nullable();
            $table->decimal('quatre_adulte_4_brut', 8, 2)->nullable();
            $table->decimal('quatre_adulte_4_total', 8, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('circuits_new');
    }
};
