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
        Schema::create('vehicle_text_keys', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('value');
            $table->unsignedBigInteger('object_id');
            $table->enum('object_type',['sale_vehicle','sale_accomm'])->nullable();
            $table->enum('status',['active', 'inactive', 'deleted'])->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_text_keys');
    }
};
