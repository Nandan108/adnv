<?php

use App\Enums\CommercialdocStatus;
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
        Schema::table('commercialdocs', function (Blueprint $table) {
            $table->enum('status', collect(CommercialdocStatus::cases())->pluck('value')->all())
                ->default(CommercialdocStatus::INITIAL_QUOTE_CREATED->value)
                ->after('object_type');
            $table->enum('title', ['Mr','Mme'])->after('client_remarques')->default('Mr');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commercialdocs', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('status');
        });
    }
};
