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
        Schema::create('tourism_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tour_site_id');
            $table->unsignedBigInteger('app_user_id')->default(0);
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email');
            $table->string('country')->nullable();
            $table->string('country_code')->nullable();
            $table->string('phone')->nullable();


            $table->integer('children')->default(0);
            $table->integer('adults')->default(0);

            $table->boolean('require_pick_up')->default(false);
            $table->boolean('require_security')->default(false);

            $table->string('status')->default('pending');
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tourism_requests');
    }
};
