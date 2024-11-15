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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('no')->nullable(); // No
            $table->string('kode_dealer')->nullable(); // Kode Dealer
            $table->string('kode_ba')->nullable(); // Kode BA
            $table->string('customer_master_sap')->nullable(); // Customer Master SAP
            $table->string('group_material')->nullable(); // Group Material
            $table->string('group_tobpm')->nullable(); // Group TOBPM
            $table->string('no_part')->nullable(); // No Part
            $table->string('nama_part')->nullable(); // Nama Part
            $table->integer('rank_part')->nullable(); // Rank Part
            $table->string('discontinue')->nullable(); // Discontinue
            $table->string('kode_gudang')->nullable(); // Kode Gudang
            $table->string('nama_gudang')->nullable(); // Nama Gudang
            $table->string('kode_lokasi')->nullable(); // Kode Lokasi
            $table->integer('int')->nullable(); // INT
            $table->integer('oh')->nullable(); // OH
            $table->integer('rsv')->nullable(); // RSV
            $table->integer('blk')->nullable(); // BLK
            $table->integer('wip')->nullable(); // WIP
            $table->integer('bok')->nullable(); // BOK
            $table->integer('total_exc_int')->nullable(); // Total Exc INT
            $table->float('stock_days_month')->nullable(); // Stock Days (Month)
            $table->float('avg_demand_qty')->nullable(); // Avg Demand Qty
            $table->float('avg_demand_amt')->nullable(); // Avg Demand Amt
            $table->float('avg_sales_monthly_qty')->nullable(); // Avg Sales Monthly Qty
            $table->float('avg_sales_monthly_amt')->nullable(); // Avg Sales Monthly Amt
            $table->float('standard_price_moving_avg_price')->nullable(); // Standard Price/Moving Average Price
            $table->float('invt_amt_exc_int')->nullable(); // Invt Amt Exc INT
            $table->timestamps();
        });
    }

    /**
     *
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
