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
        Schema::create('security_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_user_id')->default(0);
            $table->unsignedBigInteger('security_id')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email');
            $table->string('country')->nullable();
            $table->string('country_code')->nullable();
            $table->string('phone')->nullable();

            $table->unsignedBigInteger('security_client_type_id')->nullable();

            $table->unsignedBigInteger('opened_by')->nullable();
            $table->enum('status',['active', 'pending','close', 'deleted'])->default('pending');
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_requests');
    }
};
