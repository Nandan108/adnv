<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aeroport', function (Blueprint $table) {
            $table->id('id_aeroport');
            $table->char('code_aeroport', 3)->unique()->collation('latin1_general_ci');
            $table->string('aeroport', 50)->charset('utf8mb3');
            $table->unsignedInteger('id_lieu');
            $table->string('pays', 50)->collation('latin1_general_ci');
            $table->string('ville', 50)->collation('latin1_general_ci');
            $table->decimal('taxe', 3, 0)->default(0);
            $table->decimal('taxe_enfant', 3, 0)->default(0);
            $table->decimal('taxe_bebe', 3, 0)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aeroport');
    }
};
