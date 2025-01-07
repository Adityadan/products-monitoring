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
        Schema::create('rod', function (Blueprint $table) {
            $table->id();
            $table->string('kode_customer');
            $table->string('customer_name');
            $table->integer('cost_amount');
            $table->string('mat_type');
            $table->timestamp('periode')->nullable();
            $table->string('rod_type');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rod');
    }
};
