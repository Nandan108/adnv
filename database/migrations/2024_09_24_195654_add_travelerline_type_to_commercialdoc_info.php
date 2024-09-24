<?php

use App\Enums\CommercialdocInfoType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private function getAllowedInfoTypes()
    {
        return collect(CommercialdocInfoType::cases())->pluck('value')->all();
    }
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('commercialdoc_infos', function (Blueprint $table) {
            // added 'traveler_line' type
            $table->enum('type', $this->getAllowedInfoTypes())->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commercialdoc_infos', function (Blueprint $table) {
            $types = array_filter(
                $this->getAllowedInfoTypes(),
                fn($type) => $type !== 'traveler_line'
            );

            $table->enum('type', $types)->change();
        });
    }
};
