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
        Schema::create('vols_new', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('vol_seul')->default(0)->comment('Indique si le vol est à utiliser comme vol seul plutôt que dans un séjour');
            $table->string('titre', 100);
            $table->unsignedInteger('id_company');
            $table->enum('class_reservation', ['Airline', 'Charter', 'Contingent']);
            $table->unsignedTinyInteger('sans_bagage')->default(0)->comment('pour les vols seul');
            $table->char('monnaie', 3)->nullable();
            $table->float('taux_change')->default(0);
            $table->set('jours_depart', ['1','2','3','4','5','6','7'])->default('1,2,3,4,5,6,7');
            $table->char('code_apt_depart', 3);
            $table->char('code_apt_transit', 3)->nullable();
            $table->char('code_apt_arrive', 3);
            $table->boolean('arrive_next_day')->default(0)->comment('0 = arrivée même jour');
            $table->date('debut_vente');
            $table->date('fin_vente');
            $table->date('debut_voyage');
            $table->date('fin_voyage');

            $table->index('code_apt_depart');
            $table->index('code_apt_arrive');
            $table->index('debut_voyage');
            $table->index('fin_voyage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vols_new');
    }
};
