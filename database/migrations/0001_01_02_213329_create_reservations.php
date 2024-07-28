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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->char('code_pays', 2)->comment('Destination');
            $table->date('date_depart');
            $table->date('date_retour');
            $table->unsignedTinyInteger('nb_adulte')->nullable();
            $table->string('ages_enfants', 20)->nullable()->comment('comma-separated ages (int)');
            $table->unsignedTinyInteger('nb_bebe')->nullable();
            $table->unsignedInteger('id_prix_vol')->nullable();
            $table->unsignedInteger('id_transfert')->nullable();
            $table->unsignedInteger('id_chambre')->nullable();
            $table->unsignedTinyInteger('nb_chambres')->default(1);
            $table->string('nom_chambre', 50)->nullable()->comment('info pour référence, ne pas utiliser dans le code');
            $table->unsignedInteger('id_hotel')->nullable()->comment('info pour référence, ne pas utiliser dans le code');
            $table->text('contact_info')->nullable();
            $table->text('remarques')->nullable();
            $table->string('pdf_devis_filename', 40)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('client_submitted_at')->nullable();
            $table->timestamp('quote_sent_at')->nullable();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

            $table->foreign('id_prix_vol')->references('id_prix_vol')->on('vol_prix');
            $table->foreign('id_transfert')->references('id')->on('transfert_new');
            $table->foreign('id_chambre')->references('id_chambre')->on('chambre');

            $table->index('id_prix_vol');
            $table->index('id_transfert');
            $table->index('id_chambre');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
