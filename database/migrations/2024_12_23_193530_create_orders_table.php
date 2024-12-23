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
        /* Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        }); */
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('no_part');
            $table->string('kode_dealer');
            $table->string('product_name');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->integer('total_items');
            $table->string('buyer_dealer');
            $table->string('buyer_name');
            $table->string('phone');
            $table->text('shipping_address');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
