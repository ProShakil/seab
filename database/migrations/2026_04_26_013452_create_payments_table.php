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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reunion_period_id')->constrained('reunion_periods')->restrictOnDelete();
            $table->date('payment_date');
            $table->string('trx_id')->unique();
            $table->string('reference');
            $table->foreignId('payment_method')->constrained('payment_methods')->restrictOnDelete();
            $table->boolean('payment_status')->default(0);
            $table->string('receipt_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
