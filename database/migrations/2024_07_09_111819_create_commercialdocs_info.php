<?php

use App\Enums\CommercialdocInfoType;
use App\Models\Commercialdoc;
use App\Models\Monnaie;
use App\Models\Reservation;
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
        DB::statement('ALTER TABLE taux_monnaie ENGINE = InnoDB');

        Schema::create('commercialdoc_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Commercialdoc::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('type', collect(CommercialdocInfoType::cases())->pluck('value')->all());
            $table->text('data');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commercialdoc_infos');
    }
};
