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
        Schema::create('order_detail', function (Blueprint $table) {
            $table->id();
            $table->string('no_part');
            $table->string('kode_dealer');
            $table->string('product_name');
            $table->integer('qty_order')->comment('jumlah yang dipesan');
            $table->integer('qty_supply')->comment('jumlah yang dikirim')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->integer('total_items');
            $table->text('notes')->nullable();
            $table->integer('id_order')->nullable();
            $table->integer('id_shipping')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_detail');
    }
};
