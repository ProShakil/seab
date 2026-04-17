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
        Schema::create('front_messages', function (Blueprint $table) {
            $table->id();
            $table->text('president_message')->nullable();
            $table->text('vice_president_message')->nullable();
            $table->text('mission')->nullable();
            $table->text('vision')->nullable();
            $table->text('about_seab')->nullable();
            $table->text('membership_process')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('front_messages');
    }
};
