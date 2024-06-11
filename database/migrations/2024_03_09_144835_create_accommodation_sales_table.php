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
        Schema::create('accommodation_sales', function (Blueprint $table) {
            $table->id();
            $table->string('name')->require();
            $table->string('region');
            $table->string('city');
            $table->double('price')->default(0);
            $table->double('discount')->default(0);
            $table->double('ratings_value')->default(5);
            $table->boolean('available')->default(true);
            $table->enum('status',['active', 'inactive', 'deleted'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accommodation_sales');
    }
};
