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
        Schema::create('chat_lists', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('from')->nullable();
            $table->unsignedBigInteger('to')->nullable();
            $table->unsignedBigInteger('owner');
            $table->string('message');
            $table->string('type')->default('text');
            $table->unsignedBigInteger('admin')->nullable();
            $table->boolean('unread')->default(true);

            $table->unsignedBigInteger('object_id');
            $table->enum('object_type',['sale_vehicle', 'sale_accomm'])->default('sale_vehicle');
            $table->enum('status',['active', 'inactive', 'deleted'])->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_lists');
    }
};
