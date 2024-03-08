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
        Schema::create('tourisms', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('overview');
            $table->double('price')->default(0);
            $table->double('ratings_value')->default(0);
            $table->double('total_ratings')->default(0);
            $table->string('available_time')->default('11 to 15 hours');
            $table->boolean('free_cancellation')->default(true);
            $table->boolean('can_pick_up')->default(true);
            $table->boolean('can_provide_security')->default(true);



            $table->enum('status',['active', 'inactive', 'deleted'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tourisms');
    }
};
