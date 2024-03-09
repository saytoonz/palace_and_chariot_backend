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
        Schema::create('vehicle_rent_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id');


            $table->unsignedBigInteger('app_user_id')->default(0);
            $table->string('pickup_location');
            $table->timestamp('pickup_date_time');
            $table->timestamp('dropoff_date_time');

            $table->string('name');
            $table->string('address');
            $table->string('city');
            $table->string('country')->nullable();
            $table->string('country_code')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('need_our_driver')->default(true);

            $table->string('driver_title')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_email')->nullable();
            $table->string('driver_country_code')->nullable();
            $table->string('driver_country')->nullable();
            $table->string('driver_phone')->nullable();
            $table->string('driver_license')->nullable();


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
        Schema::dropIfExists('vehicle_rent_requests');
    }
};
