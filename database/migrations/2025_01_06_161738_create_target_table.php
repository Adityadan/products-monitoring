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
        Schema::create('target', function (Blueprint $table) {
            $table->id();
            $table->string('kode_channel')->nullable();
            $table->string('nama_customer')->nullable();
            $table->string('channel')->nullable();
            $table->integer('target_part')->nullable();
            $table->integer('target_oli')->nullable();
            $table->integer('target_app')->nullable();
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
        Schema::dropIfExists('target');
    }
};
