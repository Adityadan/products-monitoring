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
        Schema::create('distance_order_dealer', function (Blueprint $table) {
            $table->id();
            $table->integer('dealer_id');
            $table->string('kode_dealer');
            $table->integer('order_distance');
            $table->string('area');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distance_order_dealer');
    }
};
