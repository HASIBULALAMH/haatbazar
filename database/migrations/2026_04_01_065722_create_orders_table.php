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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');

        // Shipping info (checkout এ নেওয়া হবে)
        $table->string('shipping_name');
        $table->string('shipping_phone');
        $table->text('shipping_address');
        $table->string('shipping_city');

        // Payment
        $table->enum('payment_method', ['cod', 'bkash'])->default('cod');
        $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
        $table->string('transaction_id')->nullable(); // bKash transaction

        // Order status
        $table->enum('status', [
            'pending',
            'processing',
            'shipped',
            'delivered',
            'cancelled'
        ])->default('pending');

        // Totals
        $table->decimal('subtotal', 10, 2);
        $table->decimal('shipping_charge', 10, 2)->default(0);
        $table->decimal('total', 10, 2);

        $table->text('notes')->nullable(); // buyer notes
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
