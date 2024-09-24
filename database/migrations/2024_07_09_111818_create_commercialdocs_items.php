<?php

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
        Schema::create('commercialdoc_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Commercialdoc::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('description', 255);
            $table->decimal('unitprice', 7, 2);
            $table->tinyInteger('qtty')->unsigned()->default(1);
            $table->tinyInteger('discount_pct')->unsigned()->default(0);
            $table->enum('section', ['primary', 'options']);
            $table->enum('stage', ['initial', 'final'])->default('initial');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commercialdoc_items');
    }
};
