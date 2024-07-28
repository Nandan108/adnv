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
        Schema::create('voyageurs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('booking_id');
            $table->enum('booking_type', ['App\\Models\\Reservation', 'App\\Models\\Circuit', 'App\\Models\\Croisiere']);
            $table->tinyInteger('adulte');
            $table->tinyInteger('idx');
            $table->string('nom', 50);
            $table->string('prenom', 50);
            $table->string('titre', 10)->nullable();
            $table->string('code_pays_nationalite', 2);
            $table->date('date_naissance')->nullable();
            $table->unsignedInteger('id_assurance')->nullable();
            $table->string('options', 50)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->unique(['booking_type', 'booking_id', 'adulte', 'idx'], 'booking_type_booking_id_adulte_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voyageurs');
    }
};
