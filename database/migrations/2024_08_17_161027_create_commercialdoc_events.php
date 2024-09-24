<?php

use App\Enums\CommercialdocEventType;
use App\Models\Commercialdoc;
use App\Models\User;
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
        DB::statement('DROP TABLE IF EXISTS commercialdoc_events');
        Schema::create('commercialdoc_events', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Commercialdoc::class)
                ->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('type', collect(CommercialdocEventType::cases())->pluck('value')->all());
            $table->decimal('amount', 10, 2)->nullable(); // Payment amount, only relevant for paymentReceived
            $table->enum('payment_method', ['credit_card', 'bank_transfert', 'cash', 'other'])->nullable(); // Only relevant for paymentReceived
            $table->text('data')->default('{}'); // Any additional notes
            $table->foreignIdFor(User::class, 'admin_id')
                ->nullable()->constrained('users')
                ->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commercialdoc_events');
    }
};
