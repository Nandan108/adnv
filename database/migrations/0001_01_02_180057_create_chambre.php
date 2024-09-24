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
        Schema::create('chambre', function (Blueprint $table) {
            $table->id('id_chambre');
            $table->string('nom_chambre', 100)->nullable();
            $table->unsignedInteger('id_hotel')->default(0);
            $table->string('surclassement', 10)->default('0');
            $table->date('debut_validite')->default('0000-00-00');
            $table->date('fin_validite')->default('0000-00-00');
            $table->string('taux_change', 50)->default('0');
            $table->string('taux_commission', 50)->default('0');
            $table->string('simple_nb_max', 50)->default('0');
            $table->string('simple_adulte_max', 50)->default('0');
            $table->string('simple_enfant_max', 50)->default('0');
            $table->string('simple_bebe_max', 50)->default('0');
            $table->string('adulte_1_net', 50)->default('0');
            $table->string('adulte_1_brut', 50)->default('0');
            $table->string('adulte_1_total', 50)->default('0');
            $table->unsignedTinyInteger('de_1_enfant')->default(0);
            $table->unsignedTinyInteger('a_1_enfant')->default(0);
            $table->string('enfant_1_net', 50)->default('0');
            $table->string('enfant_1_brut', 50)->default('0');
            $table->string('enfant_1_total', 50)->default('0');
            $table->unsignedTinyInteger('de_2_enfant')->default(0);
            $table->unsignedTinyInteger('a_2_enfant')->default(0);
            $table->string('enfant_2_net', 50)->default('0');
            $table->string('enfant_2_brut', 50)->default('0');
            $table->string('enfant_2_total', 50)->default('0');
            $table->unsignedTinyInteger('de_3_enfant')->default(0);
            $table->unsignedTinyInteger('a_3_enfant')->default(0);
            $table->string('enfant_3_net', 50)->default('0');
            $table->string('enfant_3_brut', 50)->default('0');
            $table->string('enfant_3_total', 50)->default('0');
            $table->string('bebe_1', 50)->default('0');
            $table->string('bebe_1_net', 50)->default('0');
            $table->string('bebe_1_brut', 50)->default('0');
            $table->string('bebe_1_total', 50)->default('0');
            $table->string('double_nb_max', 50)->default('0');
            $table->string('double_adulte_max', 50)->default('0');
            $table->string('double_enfant_max', 50)->default('0');
            $table->string('double_bebe_max', 50)->default('0');
            $table->string('double_adulte_1_net', 50)->default('0');
            $table->string('double_adulte_1_brut', 50)->default('0');
            $table->decimal('double_adulte_1_total', 10, 2)->unsigned()->default(0.00);
            $table->string('double_adulte_2_net', 50)->default('0');
            $table->string('double_adulte_2_brut', 50)->default('0');
            $table->string('double_adulte_2_total', 50)->default('0');
            $table->string('double_de_1_enfant', 50)->default('0');
            $table->string('double_a_1_enfant', 50)->default('0');
            $table->string('double_enfant_1_net', 50)->default('0');
            $table->string('double_enfant_1_brut', 50)->default('0');
            $table->string('double_enfant_1_total', 50)->default('0');
            $table->string('double_de_2_enfant', 50)->default('0');
            $table->string('double_a_2_enfant', 50)->default('0');
            $table->string('double_enfant_2_net', 50)->default('0');
            $table->string('double_enfant_2_brut', 50)->default('0');
            $table->string('double_enfant_2_total', 50)->default('0');
            $table->string('double_de_3_enfant', 50)->default('0');
            $table->string('double_a_3_enfant', 50)->default('0');
            $table->string('double_enfant_3_net', 50)->default('0');
            $table->string('double_enfant_3_brut', 50)->default('0');
            $table->string('double_enfant_3_total', 50)->default('0');
            $table->string('double_bebe_1', 50)->default('0');
            $table->string('double_bebe_1_net', 50)->default('0');
            $table->string('double_bebe_1_brut', 50)->default('0');
            $table->string('double_bebe_1_total', 50)->default('0');
            $table->string('tripple_nb_max', 50)->default('0');
            $table->string('tripple_adulte_max', 50)->default('0');
            $table->string('tripple_adulte_1_net', 50)->default('0');
            $table->string('tripple_adulte_1_brut', 50)->default('0');
            $table->string('tripple_adulte_1_total', 50)->default('0');
            $table->string('tripple_adulte_2_net', 50)->default('0');
            $table->string('tripple_adulte_2_brut', 50)->default('0');
            $table->string('tripple_adulte_2_total', 50)->default('0');
            $table->string('tripple_adulte_3_net', 50)->default('0');
            $table->string('tripple_adulte_3_brut', 50)->default('0');
            $table->string('tripple_adulte_3_total', 50)->default('0');
            $table->string('quatre_nb_max', 50)->default('0');
            $table->string('quatre_adulte_max', 50)->default('0');
            $table->string('quatre_adulte_1_net', 50)->default('0');
            $table->string('quatre_adulte_1_brut', 50)->default('0');
            $table->string('quatre_adulte_1_total', 50)->default('0');
            $table->string('quatre_adulte_2_net', 50)->default('0');
            $table->string('quatre_adulte_2_brut', 50)->default('0');
            $table->string('quatre_adulte_2_total', 50)->default('0');
            $table->string('quatre_adulte_3_net', 50)->default('0');
            $table->string('quatre_adulte_3_brut', 50)->default('0');
            $table->string('quatre_adulte_3_total', 50)->default('0');
            $table->string('quatre_adulte_4_net', 50)->default('0');
            $table->string('quatre_adulte_4_brut', 50)->default('0');
            $table->string('quatre_adulte_4_total', 50)->default('0');
            $table->string('club', 250)->nullable();
            $table->string('remise', 10)->nullable();
            $table->enum('unite', ['pourcentage', 'chf'])->nullable();
            $table->string('debut_remise', 50)->nullable();
            $table->string('fin_remise', 50)->nullable();
            $table->string('debut_remise_voyage', 50)->nullable();
            $table->string('fin_remise_voyage', 50)->nullable();
            $table->integer('status_remise', false, true)->nullable();
            $table->string('code_promo', 40)->nullable();
            $table->string('remise2', 50)->nullable();
            $table->enum('unite2', ['pourcentage', 'chf'])->nullable();
            $table->string('debut_remise2', 50)->nullable();
            $table->string('fin_remise2', 50)->nullable();
            $table->string('debut_remise2_voyage', 50)->nullable();
            $table->string('fin_remise2_voyage', 50)->nullable();
            $table->string('code_promo2', 50)->nullable();
            $table->string('photo_chambre', 250)->nullable();
            $table->integer('tripple_enfant_max', false, true)->default(0);
            $table->string('tripple_adulte_3_net_enfant', 10)->default('0');
            $table->string('tripple_adulte_3_brut_enfant', 10)->default('0');
            $table->string('tripple_adulte_3_total_enfant', 10)->default('0');
            $table->string('a', 10)->default('0');
            $table->string('b', 10)->default('0');
            $table->string('monnaie', 10)->default('0');
            $table->integer('villa_nb_max', false, true)->default(0);
            $table->integer('villa_adulte_max', false, true)->default(0);
            $table->string('villa_adulte_1_net', 10)->default('0');
            $table->string('villa_adulte_1_brut', 10)->nullable();
            $table->string('villa_adulte_1_total', 10)->default('0');
            $table->date('disabled')->nullable();
            $table->string('quatre_adulte_4_total_chf', 9)->default('0');
            $table->string('quatre_adulte_4_net_chf', 9)->default('0');
            $table->string('quatre_adulte_3_total_chf', 9)->default('0');
            $table->string('quatre_adulte_3_net_chf', 9)->default('0');
            $table->string('quatre_adulte_2_total_chf', 9)->default('0');
            $table->string('quatre_adulte_2_net_chf', 9)->default('0');
            $table->string('quatre_adulte_1_total_chf', 9)->default('0');
            $table->string('quatre_adulte_1_net_chf', 9)->default('0');
            $table->string('tripple_adulte_3_total_chf', 9)->default('0');
            $table->string('tripple_adulte_3_net_chf', 9)->default('0');
            $table->string('tripple_adulte_2_total_chf', 9)->default('0');
            $table->string('tripple_adulte_2_net_chf', 9)->default('0');
            $table->string('tripple_adulte_1_total_chf', 9)->default('0');
            $table->string('tripple_adulte_1_net_chf', 9)->default('0');
            $table->string('double_bebe_1_total_chf', 9)->default('0');
            $table->string('double_bebe_1_net_chf', 9)->default('0');
            $table->string('adulte_enfant_3_net_chf', 9)->default('0');
            $table->string('double_enfant_3_total_chf', 9)->default('0');
            $table->string('double_enfant_2_total_chf', 9)->default('0');
            $table->string('double_enfant_2_net_chf', 9)->default('0');
            $table->string('double_enfant_1_total_chf', 9)->default('0');
            $table->string('double_enfant_1_net_chf', 9)->default('0');
            $table->string('double_adulte_2_total_chf', 9)->default('0');
            $table->string('double_adulte_2_net_chf', 9)->default('0');
            $table->string('double_adulte_1_total_chf', 9)->default('0');
            $table->string('double_adulte_1_net_chf', 9)->default('0');
            $table->string('bebe_1_total_chf', 9)->default('0');
            $table->string('bebe_1_net_chf', 9)->default('0');
            $table->string('enfant_3_total_chf', 9)->default('0');
            $table->string('enfant_3_net_chf', 9)->default('0');
            $table->string('enfant_2_total_chf', 9)->default('0');
            $table->string('enfant_2_net_chf', 9)->default('0');
            $table->string('enfant_1_total_chf', 9)->default('0');
            $table->string('enfant_1_net_chf', 9)->default('0');
            $table->string('adulte_1_total_chf', 9)->default('0');
            $table->string('adulte_1_net_chf', 9)->default('0');
            $table->string('quatre_adulte_4_total_pourcentage', 9)->default('0');
            $table->string('quatre_adulte_4_net_pourcentage', 9)->default('0');
            $table->string('quatre_adulte_3_total_pourcentage', 9)->default('0');
            $table->string('quatre_adulte_3_net_pourcentage', 9)->default('0');
            $table->string('quatre_adulte_2_total_pourcentage', 9)->default('0');
            $table->string('quatre_adulte_2_net_pourcentage', 9)->default('0');
            $table->string('quatre_adulte_1_total_pourcentage', 9)->default('0');
            $table->string('quatre_adulte_1_net_pourcentage', 9)->default('0');
            $table->string('tripple_adulte_3_total_pourcentage', 9)->default('0');
            $table->string('tripple_adulte_3_net_pourcentage', 9)->default('0');
            $table->string('tripple_adulte_2_total_pourcentage', 9)->default('0');
            $table->string('tripple_adulte_2_net_pourcentage', 9)->default('0');
            $table->string('tripple_adulte_1_total_pourcentage', 9)->default('0');
            $table->string('tripple_adulte_1_net_pourcentage', 9)->default('0');
            $table->string('double_bebe_1_total_pourcentage', 9)->default('0');
            $table->string('double_bebe_1_net_pourcentage', 9)->default('0');
            $table->string('adulte_enfant_3_net_pourcentage', 9)->default('0');
            $table->string('double_enfant_3_total_pourcentage', 9)->default('0');
            $table->string('double_enfant_2_total_pourcentage', 11)->default('0');
            $table->string('double_enfant_2_net_pourcentage', 11)->default('0');
            $table->string('double_enfant_1_total_pourcentage', 11)->default('0');
            $table->string('double_enfant_1_net_pourcentage', 11)->default('0');
            $table->string('double_adulte_2_total_pourcentage', 11)->default('0');
            $table->string('double_adulte_2_net_pourcentage', 11)->default('0');
            $table->string('double_adulte_1_total_pourcentage', 11)->default('0');
            $table->string('double_adulte_1_net_pourcentage', 11)->default('0');
            $table->string('bebe_1_total_pourcentage', 11)->default('0');
            $table->string('bebe_1_net_pourcentage', 11)->default('0');
            $table->string('enfant_3_total_pourcentage', 11)->default('0');
            $table->string('enfant_3_net_pourcentage', 11)->default('0');
            $table->string('enfant_2_total_pourcentage', 10)->default('0');
            $table->string('enfant_2_net_pourcentage', 10)->default('0');
            $table->string('enfant_1_total_pourcentage', 10)->default('0');
            $table->string('enfant_1_net_pourcentage', 10)->default('0');
            $table->string('adulte_1_total_pourcentage', 10)->default('0');
            $table->string('adulte_1_net_pourcentage', 10)->default('0');

            // Adding the virtual/generated columns using raw SQL
        });

        DB::statement("ALTER TABLE chambre ADD COLUMN _villa TINYINT(3) UNSIGNED GENERATED ALWAYS AS (villa_nb_max > 0) VIRTUAL");
        DB::statement("ALTER TABLE chambre ADD COLUMN _nb_min TINYINT(3) UNSIGNED GENERATED ALWAYS AS (IFNULL(NULLIF(LEAST(IFNULL(NULLIF((simple_nb_max > 0) * simple_adulte_max,0) + 0,99),IFNULL(NULLIF((double_nb_max > 0) * double_adulte_max,0) + 0,99),IFNULL(NULLIF((tripple_nb_max > 0) * tripple_adulte_max,0) + 0,99),IFNULL(NULLIF((quatre_nb_max > 0) * quatre_adulte_max,0) + 0,99)),99),1)) VIRTUAL");
        DB::statement("ALTER TABLE chambre ADD COLUMN _nb_max TINYINT(3) UNSIGNED GENERATED ALWAYS AS (GREATEST(simple_nb_max,double_nb_max,tripple_nb_max,quatre_nb_max,villa_nb_max) + 0) VIRTUAL");
        DB::statement("ALTER TABLE chambre ADD COLUMN _nb_max_adulte TINYINT(3) UNSIGNED GENERATED ALWAYS AS (GREATEST((simple_nb_max > 0) * simple_adulte_max,(double_nb_max > 0) * double_adulte_max,(tripple_nb_max > 0) * tripple_adulte_max,(quatre_nb_max > 0) * quatre_adulte_max,villa_nb_max)) VIRTUAL");
        DB::statement("ALTER TABLE chambre ADD COLUMN _nb_max_enfant TINYINT(3) UNSIGNED GENERATED ALWAYS AS (GREATEST(simple_enfant_max,double_enfant_max,tripple_enfant_max,villa_nb_max - 1)) VIRTUAL");
        DB::statement("ALTER TABLE chambre ADD COLUMN _nb_max_bebe TINYINT(3) UNSIGNED GENERATED ALWAYS AS (GREATEST(simple_bebe_max,double_bebe_max)) VIRTUAL");
        DB::statement("ALTER TABLE chambre ADD COLUMN _age_max_bebe TINYINT(3) UNSIGNED GENERATED ALWAYS AS (GREATEST(0,(simple_bebe_max > 0 AND bebe_1 > '') * bebe_1,(double_bebe_max > 0 AND double_bebe_1 > '') * double_bebe_1,(_nb_max_bebe <> 0 AND _nb_max_enfant <> 0) * CAST(de_1_enfant AS SIGNED) - 1,(_nb_max_bebe <> 0 AND _nb_max_enfant <> 0) * CAST(double_de_1_enfant AS SIGNED) - 1)) VIRTUAL");
        DB::statement("ALTER TABLE chambre ADD COLUMN _age_max_petit_enfant TINYINT(3) UNSIGNED GENERATED ALWAYS AS (COALESCE(IF(simple_enfant_max = 0,NULL,a_1_enfant),IF(double_enfant_max = 0,NULL,double_a_1_enfant))) VIRTUAL");
        DB::statement("ALTER TABLE chambre ADD COLUMN _age_max_enfant TINYINT(3) UNSIGNED GENERATED ALWAYS AS (NULLIF((_nb_max_enfant > 0) * GREATEST(a_1_enfant,a_2_enfant,double_a_1_enfant,double_a_2_enfant),0)) VIRTUAL");
        DB::statement("ALTER TABLE chambre ADD COLUMN _enfant_2_net DECIMAL(5,1) UNSIGNED GENERATED ALWAYS AS (GREATEST(enfant_1_net + 0,enfant_2_net + 0,enfant_3_net + 0,double_enfant_1_net + 0,double_enfant_2_net + 0,double_enfant_3_net + 0)) VIRTUAL COMMENT 'appliqué au 1er enfant si age <= age_max_petit_enfant'");
        DB::statement("ALTER TABLE chambre ADD COLUMN _enfant_1_net DECIMAL(5,1) UNSIGNED GENERATED ALWAYS AS (IF(_age_max_petit_enfant > 0,GREATEST(enfant_1_net,double_enfant_1_net),NULL)) VIRTUAL COMMENT 'appliqué au 2èm enfant, et au 1er enfant si son age > age_max_petit_enfant'");
        DB::statement("ALTER TABLE chambre ADD COLUMN _adulte_1_net_a DECIMAL(6,2) GENERATED ALWAYS AS (a) VIRTUAL");
        DB::statement("ALTER TABLE chambre ADD COLUMN _adulte_1_net_b DECIMAL(6,2) GENERATED ALWAYS AS (b) VIRTUAL");
        DB::statement("ALTER TABLE chambre ADD COLUMN _adulte_1_net DECIMAL(6,2) GENERATED ALWAYS AS (IF(_villa,villa_adulte_1_net,IF(_nb_min = 1,CEILING(a + b),NULL))) VIRTUAL COMMENT 'appliqué s''il n''y a qu''un adulte.'");
        DB::statement("ALTER TABLE chambre ADD COLUMN _adulte_2_net DECIMAL(6,2) GENERATED ALWAYS AS (IF(_nb_max_adulte < 2,NULL,double_adulte_2_net)) VIRTUAL COMMENT 'appliqué aux 1er et 2èm adultes si nb_adultes > 1'");
        DB::statement("ALTER TABLE chambre ADD COLUMN _adulte_3_net DECIMAL(6,2) GENERATED ALWAYS AS (IF(_nb_max_adulte < 3,NULL,NULLIF(GREATEST(tripple_adulte_3_net,quatre_adulte_3_net),_adulte_1_net))) VIRTUAL");
        DB::statement("ALTER TABLE chambre ADD COLUMN _adulte_4_net DECIMAL(6,2) GENERATED ALWAYS AS (IF(_nb_max_adulte < 4,NULL,NULLIF(quatre_adulte_3_net,_adulte_3_net))) VIRTUAL");
        DB::statement("ALTER TABLE chambre ADD COLUMN _bebe_1_net DECIMAL(6,2) GENERATED ALWAYS AS (IF(_nb_max_bebe,bebe_1_net,NULL)) VIRTUAL");

        // Adding indexes and keys
        DB::statement("ALTER TABLE chambre ADD PRIMARY KEY (`id_chambre`)");
        DB::statement("ALTER TABLE chambre ADD KEY chambre_hotel_FK (`id_hotel`)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chambre');
    }
};
