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
        Schema::create('dealers', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique(); // Kode Dealer
            $table->string('ahass'); // AHASS
            $table->string('kota_kab'); // Kota/ Kab
            $table->string('kecamatan'); // Kecamatan
            $table->string('status'); // Status
            $table->string('se_area'); // SE Area
            $table->string('group'); // Group
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealers');
    }
};
