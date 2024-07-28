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
        Schema::create('reservation_tour', function (Blueprint $table) {
            $table->unsignedInteger('reservation_id');
            $table->unsignedInteger('tour_id');
            $table->unsignedTinyInteger('adulte')->default(0)->comment('number of adultes');
            $table->unsignedTinyInteger('enfant')->default(0)->comment('number of children');
            $table->unsignedTinyInteger('bebe')->default(0)->comment('number of babies');

            $table->foreign('tour_id')->references('id')->on('tours_new');
            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');

            $table->index('tour_id', 'tours_id');
            $table->index('reservation_id', 'reservation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_tour');
    }
};
