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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('membership_id')->nullable();
            $table->string('user_name')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('name');
            $table->string('user_fathers_name')->nullable();
            $table->string('user_mothers_name')->nullable();
            $table->string('dob', 20)->nullable();
            $table->integer('id_type')->nullable();
            $table->string('national_identity_number', 30)->nullable();
            $table->string('gender', 10)->nullable();
            $table->string('present_address_detail', 500)->nullable();
            $table->string('permanent_address_details', 500)->nullable();
            $table->string('contact_no', 20)->nullable();
            $table->foreignId('tech_id')->nullable()->constrained('technologies');
            $table->foreignId('occupation_id')->nullable()->constrained('occupations');
            $table->string('employer_name', 300)->nullable();
            $table->string('designation', 200)->nullable();
            $table->string('office_address_details', 500)->nullable();
            $table->string('latest_degree_name', 100)->nullable();
            $table->string('latest_institute_name', 300)->nullable();
            $table->foreignId('membership_type_id')->nullable()->constrained('membership_types');
            $table->integer('ex_association')->nullable();
            $table->string('ex_association_details', 1000)->nullable();
            $table->string('emergency_contact_name', 200)->nullable();
            $table->foreignId('relationship_id')->nullable()->constrained('relationships');
            $table->string('emergency_contact_no', 20)->nullable();
            $table->string('profile_image', 300)->nullable();
            $table->string('signature', 300)->nullable();
            $table->string('union_name',100)->nullable();
            $table->boolean('is_admin')->default(0);
            $table->integer('admin_role_id')->nullable();
            $table->integer('data_status')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
