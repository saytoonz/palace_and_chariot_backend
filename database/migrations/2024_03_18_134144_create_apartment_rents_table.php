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
        Schema::create('apartment_rents', function (Blueprint $table) {
            $table->id();
            $table->string('name')->require();
            $table->string('country')->default('Ghana');
            $table->string('region')->nullable();
            $table->string('city')->nullable();
            $table->double('price')->default(0);
            $table->string('room_desc')->nullable();
            $table->double('ratings_value')->default(5);
            $table->integer('total_reviews')->default(0);
            $table->boolean('available')->default(true);
            $table->double('distance_away')->default(0);
            $table->double('lat')->default(0);
            $table->double('lng')->default(0);
            $table->string('address')->nullable();
            $table->enum('status', ['active', 'inactive', 'deleted'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartment_rents');
    }
};
