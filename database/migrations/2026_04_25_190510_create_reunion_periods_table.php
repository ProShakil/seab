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
        Schema::create('reunion_periods', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->decimal('fee', 10, 2)->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('receipt_model')->nullable();
            $table->boolean('data_status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reunion_periods');
    }
};
