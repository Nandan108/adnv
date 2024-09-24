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
        DB::statement('ALTER TABLE taux_monnaie ENGINE = InnoDB');

        Schema::dropIfExists('commercialdocs');
        Schema::create('commercialdocs', function (Blueprint $table) {
            $table->id();
            $table->string('doc_id', 10);
            $table->enum('type', ['quote', 'invoice'])->default('quote');
            // $table->int('parent_document_id')->unsigned()->nullable();
            $table->char('currency_code', 3)->charset('latin1')->collation('latin1_general_ci');
            $table->foreign('currency_code', 'fk_commercialdocs_currency_code')
                ->references('code')->on('taux_monnaie')
                ->restrictOnDelete()->cascadeOnUpdate();
            $table->integer('reservation_id')->unsigned();
            $table->foreign('reservation_id', 'fk_commerciadocs_reservation_id')
                ->references('id')->on('reservations')
                ->restrictOnDelete()->cascadeOnUpdate();
            $table->date('deadline')->nullable();
            $table->enum('object_type', ['trip', 'circuit', 'cruise']);
            $table->string('client_remarques', 500)->nullable();
            $table->string('lastname',  45);
            $table->string('firstname', 30);
            $table->string('email', 30);
            $table->string('phone', 30);
            $table->string('street', 200);
            $table->string('street_num', 5)->nullable();
            $table->string('zip', 10);
            $table->string('city', 40);
            $table->string('country_code', 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commercialdoc_infos');
        Schema::dropIfExists('commercialdoc_items');
        Schema::dropIfExists('commercialdocs');
    }
};
