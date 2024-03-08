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
        Schema::create('travel_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_user_id')->default(0);
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email');
            $table->string('country')->nullable();
            $table->string('country_code')->nullable();
            $table->string('phone')->nullable();

            $table->unsignedBigInteger('depart_location_id')->nullable();
            $table->string('depart_date')->nullable();
            $table->unsignedBigInteger('return_location_id')->nullable();
            $table->string('return_date')->nullable();


            $table->integer('children')->default(0);
            $table->integer('adults')->default(0);

            $table->boolean('require_dropoff')->default(false);
            $table->boolean('require_pick_up')->default(false);
            $table->boolean('require_provide_security')->default(false);
            $table->boolean('require_provide_tour')->default(false);
            $table->boolean('require_provide_accommodation')->default(false);
            $table->boolean('require_provide_rentals')->default(false);

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
        Schema::dropIfExists('travel_requests');
    }
};
