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
        Schema::create('summary_rod', function (Blueprint $table) {
            $table->id();
            $table->string('kode_customer');
            $table->string('customer_name');
            $table->integer('total_amount_tire');
            $table->integer('total_amount_oil');
            $table->integer('total_amount_app');
            $table->timestamp('periode')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('summary_rod');
    }
};
