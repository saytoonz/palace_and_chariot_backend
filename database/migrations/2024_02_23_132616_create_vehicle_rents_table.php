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
        Schema::create('vehicle_rents', function (Blueprint $table) {
            $table->id();
            $table->string('name')->require();
            $table->unsignedBigInteger('vehicle_make_id');
            $table->string('model')->nullable();
            $table->string('color')->nullable();
            $table->double('price')->default(0);
            $table->double('discount')->default(0);
            $table->integer('quantity')->default(1);
            $table->double('driver_fee')->default(0);
            $table->string('location')->nullable();
            $table->double('ratings_value')->default(5);
            $table->double('distance_away')->default(0);
            $table->string('free_cancellation_after')->nullable();
            $table->boolean('available')->default(true);
            $table->enum('type',['car', 'bus', 'jet'])->default('car');
            $table->enum('status',['active', 'inactive', 'deleted'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_rents');
    }
};
