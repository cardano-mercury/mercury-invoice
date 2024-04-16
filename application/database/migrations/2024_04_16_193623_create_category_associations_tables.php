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
        Schema::create('customer_categories', static function (Blueprint $table) {
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('category_id')->constrained('categories');
        });

        Schema::create('product_categories', static function (Blueprint $table) {
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('category_id')->constrained('categories');
        });

        Schema::create('service_categories', static function (Blueprint $table) {
            $table->foreignId('service_id')->constrained('services');
            $table->foreignId('category_id')->constrained('categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_categories');
        Schema::dropIfExists('product_categories');
    }
};
