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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->require();
            $table->string('image')->require();
            $table->double('price')->default(0);
            $table->double('discount')->default(0);
            $table->integer('adults')->default(0);
            $table->integer('children')->default(0);
            $table->unsignedBigInteger('object_id')->nullable();
            $table->enum('object_type', ['rent_apartment', 'rent_hotel'])->default('rent_apartment');
            $table->enum('status', ['active', 'inactive', 'deleted'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
