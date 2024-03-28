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
        Schema::create('event_rent_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_user_id')->default(0);
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email');
            $table->string('country')->nullable();
            $table->string('country_code')->nullable();
            $table->string('phone')->nullable();

            $table->unsignedBigInteger('rent_event_id')->nullable();
            $table->string('check_in')->nullable();
            $table->string('check_out')->nullable();
            $table->integer('capacity')->default(100);
            $table->string('event_type')->default('other');
            $table->string('other_interests')->nullable();

            $table->boolean('need_decoration')->default(false);
            $table->boolean('need_event_coverage')->default(false);
            $table->boolean('need_security')->default(false);
            $table->boolean('need_transportation')->default(false);

            $table->string('status')->default('pending');
            $table->boolean('is_deleted')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_rent_requests');
    }
};
