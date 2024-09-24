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
        Schema::table('commercialdoc_infos', function (Blueprint $table) {
            $table->tinyInteger('ord')->unsigned()->default(0)->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commercialdoc_infos', function (Blueprint $table) {
            $table->dropColumn('ord');
        });
    }
};
