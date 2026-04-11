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
    Schema::create('order_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained()->onDelete('cascade');
        $table->foreignId('product_id')->constrained()->onDelete('cascade');
        $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');

        // Snapshot — order এর সময় price lock করে রাখা
        $table->string('product_name');
        $table->decimal('price', 10, 2);        // actual paid price
        $table->decimal('original_price', 10, 2); // discount আগের price
        $table->integer('quantity');
        $table->decimal('subtotal', 10, 2);     // price × quantity

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
