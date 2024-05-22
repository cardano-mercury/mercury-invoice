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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices');
            $table->unsignedBigInteger('product_id')->index()->nullable();
            $table->unsignedBigInteger('service_id')->index()->nullable();
            $table->string('sku', 32)->nullable();
            $table->text('description')->nullable();
            $table->decimal('quantity', 18, 6);
            $table->decimal('unit_price', 18, 6);
            $table->decimal('tax_rate', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
