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
            $table->string('payment_reference', 64)->nullable();
            $table->string('crypto_asset_name', 128)->nullable();
            $table->decimal('crypto_asset_ada_price', 18, 6)->nullable();
            $table->decimal('crypto_asset_quantity', 18, 6)->nullable();
            $table->string('crypto_wallet_name', 128)->nullable();
            $table->unsignedInteger('crypto_payment_ttl')->nullable();
            $table->string('crypto_payment_recipient_address', 128)->nullable();
            $table->unsignedInteger('crypto_payment_last_checked')->default(0);
            $table->unsignedInteger('crypto_payment_process_attempts')->default(0);
            $table->text('crypto_payment_last_error')->nullable();
            $table->string('status', 32);
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
