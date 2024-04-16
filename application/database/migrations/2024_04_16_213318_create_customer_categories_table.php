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
        Schema::create('customer_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('name', 64);
            $table->unique(['user_id', 'name']);
            $table->timestamps();
        });

        Schema::create('customer_category_associations', static function (Blueprint $table) {
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('customer_category_id')->constrained('customer_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_category_associations');
        Schema::dropIfExists('customer_categories');
    }
};
