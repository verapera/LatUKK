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
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id('penjualan_id')->nullable();
            $table->unsignedBigInteger('kode_penjualan')->nullable();
            $table->date('tanggal_penjualan')->nullable();
            $table->decimal('pembayaran',10,2)->nullable();
            $table->decimal('total_harga',10,2)->nullable();
            $table->unsignedBigInteger('pelanggan_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};
