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
        Schema::create('travel_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('abrv_name');
            $table->string('airport');
            $table->boolean('can_dropoff')->default(true);
            $table->boolean('can_pick_up')->default(true);
            $table->boolean('can_provide_security')->default(true);
            $table->boolean('can_provide_tour')->default(true);
            $table->boolean('can_provide_accommodation')->default(true);
            $table->boolean('can_provide_rentals')->default(true);
            $table->enum('status',['active', 'inactive', 'deleted'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_locations');
    }
};
