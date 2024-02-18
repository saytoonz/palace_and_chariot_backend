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
        Schema::create('app_user_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_user_id');
            $table->boolean('chat_box')->default(true);
            $table->boolean('travel_ideas')->default(true);
            $table->boolean('rentals')->default(true);
            $table->boolean('security')->default(true);
            $table->boolean('sales')->default(true);
            $table->boolean('upcoming_deals')->default(true);

            $table->foreign('app_user_id')->references('id')->on('app_users')
            ->oncascade('delete');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_user_notifications');
    }
};
