<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lieux', function (Blueprint $table) {
            $table->id('id_lieu');
            $table->string('code_pays', 2)->nullable();
            $table->string('pays', 50);
            $table->string('region', 50)->nullable();
            $table->string('ville', 50);
            $table->string('lieu', 150)->default('');
            $table->string('photo_lieu', 250)->nullable();

            // Adding keys and virtual/generated columns using raw SQL
        });

        DB::statement("ALTER TABLE lieux ADD COLUMN ville_key VARCHAR(55) GENERATED ALWAYS AS (CONCAT(CONVERT(code_pays USING utf8mb3),'-',ville)) VIRTUAL COMMENT 'Use this for JOINs by ville'");
        DB::statement("ALTER TABLE lieux ADD COLUMN region_key VARCHAR(55) GENERATED ALWAYS AS (CRC32(CONCAT(CONVERT(code_pays USING utf8mb3),'-',region))) STORED COMMENT 'Use this for JOINs by region'");
        DB::statement("ALTER TABLE lieux ADD PRIMARY KEY (id_lieu)");
        DB::statement("ALTER TABLE lieux ADD KEY region_key (region_key)");
        DB::statement("ALTER TABLE lieux ADD KEY code_pays (code_pays,region)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lieux');
    }
};
