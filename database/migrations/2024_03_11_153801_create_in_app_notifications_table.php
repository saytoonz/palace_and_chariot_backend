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
        Schema::create('in_app_notifications', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('body');
            $table->string('object_id');
            $table->string('image');
            $table->enum('object_type',['security','rent_vehicle', 'sale_vehicle', 'travel', 'tour','sale_accomm'])->default('sale_vehicle');

            $table->text('app_users');

            $table->enum('status',['active', 'inactive', 'deleted'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('in_app_notifications');
    }
};
