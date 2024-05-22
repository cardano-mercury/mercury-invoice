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
        Schema::create('invoice_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices');
            $table->date('payment_date');
            $table->string('payment_method', 32);
            $table->string('payment_currency', 64)->nullable();
            $table->decimal('payment_amount', 18, 6);
            $table->string('payment_reference', 64);
            $table->string('crypto_asset_name', 128)->nullable();
            $table->decimal('crypto_asset_ada_price', 18, 6)->nullable();
            $table->unsignedBigInteger('crypto_asset_quantity')->nullable();
            $table->string('status', 16);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_payments');
    }
};
