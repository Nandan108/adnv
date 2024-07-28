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
        Schema::create('reservation_prestation', function (Blueprint $table) {
            $table->unsignedInteger('reservation_id');
            $table->unsignedInteger('prestation_id');
            $table->unsignedTinyInteger('adulte')->default(0)->comment('number of adultes');
            $table->unsignedTinyInteger('enfant')->default(0)->comment('number of children');
            $table->unsignedTinyInteger('bebe')->default(0)->comment('number of babies');

            $table->foreign('prestation_id')->references('id')->on('prestations');
            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');

            $table->index('prestation_id', 'id_prestation');
            $table->index('reservation_id', 'id_reservation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_prestation');
    }
};
