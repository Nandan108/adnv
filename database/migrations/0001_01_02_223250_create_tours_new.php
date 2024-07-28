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
        Schema::create('tours_new', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 50);
            $table->unsignedInteger('id_partenaire');
            $table->unsignedInteger('id_lieu');
            $table->string('jours_depart', 20);
            $table->string('langue', 20);
            $table->date('debut_validite');
            $table->date('fin_validite');
            $table->char('monnaie', 3)->nullable();
            $table->float('taux_change');
            $table->unsignedTinyInteger('taux_commission')->default(0);
            $table->unsignedInteger('id_type_tour');
            $table->string('duree', 25);
            $table->string('photo', 40);
            $table->decimal('prix_net_adulte', 7, 2)->default(0.00);
            $table->decimal('prix_total_adulte', 7, 2)->default(0.00);
            $table->decimal('prix_net_enfant', 7, 2)->default(0.00);
            $table->decimal('prix_total_enfant', 7, 2)->default(0.00);
            $table->decimal('prix_net_bebe', 7, 2)->default(0.00);
            $table->decimal('prix_total_bebe', 7, 2)->default(0.00);
            $table->string('detail', 500)->default('');
            $table->text('programme')->default('');
            $table->text('inclus')->default('');
            $table->text('noninclus')->default('');
            $table->string('duree_trajet', 100)->default('');
            $table->enum('facilite', ['Facile', 'Moyen', 'Difficile'])->default('Facile');
            $table->string('accessibiltes', 200)->default('');
            $table->string('recommandations', 100);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tours_new');
    }
};
