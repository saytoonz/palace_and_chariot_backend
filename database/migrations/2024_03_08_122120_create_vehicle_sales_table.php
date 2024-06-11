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
        Schema::create('vehicle_sales', function (Blueprint $table) {
            $table->id();
            $table->string('name')->require();
            $table->unsignedBigInteger('vehicle_make_id');
            $table->string('model')->nullable();
            $table->string('color')->nullable();
            $table->double('price')->default(0);
            $table->double('ratings_value')->default(5);
            $table->boolean('available')->default(true);
            $table->double('discount')->default(0);
            $table->int('quantity')->default(1);
            $table->enum('status',['active', 'inactive', 'deleted'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_sales');
    }
};
