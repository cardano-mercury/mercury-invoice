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
        Schema::create('service_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('name', 64);
            $table->unique(['user_id', 'name']);
            $table->timestamps();
        });

        Schema::create('service_category_associations', static function (Blueprint $table) {
            $table->foreignId('service_id')->constrained('services');
            $table->foreignId('service_category_id')->constrained('service_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_category_associations');
        Schema::dropIfExists('service_categories');
    }
};
