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
        Schema::create('expeditions', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name', 255); // Nama ekspedisi
            $table->string('code', 50)->unique(); // Kode unik
            $table->string('logo', 255)->nullable(); // URL logo (opsional)
            $table->string('contact_number', 50)->nullable(); // Nomor kontak
            $table->string('email', 100)->nullable(); // Email resmi
            $table->string('website', 255)->nullable(); // Website resmi
            $table->text('address')->nullable(); // Alamat kantor
            $table->boolean('is_active')->default(true); // Status aktif/tidak
            $table->timestamps(); // Kolom created_at dan updated_at
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expeditions');
    }
};
