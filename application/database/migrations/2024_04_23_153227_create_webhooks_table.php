<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('webhooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('url', 2048);
            $table->string('hmac_secret', 512);
            $table->string('hmac_algorithm', 16);
            $table->unsignedTinyInteger('max_attempts');
            $table->unsignedTinyInteger('timeout_seconds');
            $table->unsignedTinyInteger('retry_seconds');
            $table->boolean('enabled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhooks');
    }
};
