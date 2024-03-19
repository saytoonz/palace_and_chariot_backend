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
        Schema::create('vehicle_keys', function (Blueprint $table) {
            $table->id();
            $table->string('icon');
            $table->string('name');
            $table->unsignedBigInteger('object_id');
            $table->enum('object_type',['vehicle_rent', 'rent_event','rent_apartment', 'rent_hotel', 'sale_vehicle','sale_accomm','room'])->nullable();
            $table->enum('status',['active', 'inactive', 'deleted'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_keys');
    }
};
