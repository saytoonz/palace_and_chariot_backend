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
        Schema::create('dashboard_users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('employee_id')->nullable();
            $table->string('date_employed')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();

            $table->string('last_login')->nullable();
            $table->string('image_url')->nullable();
            $table->enum('access', ['standard', 'admin'])->default('standard');
            //
            $table->boolean('request_confirmation_notifiction')->default(true);
            $table->boolean('request_change_notifiction')->default(true);
            $table->boolean('email_notifiction')->default(true);
            //
            $table->string('rest_pass_code')->nullable();
            //
            $table->enum('status', ['active', 'inactive', 'pending'])->default('pending');
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dashboard_users');
    }
};
