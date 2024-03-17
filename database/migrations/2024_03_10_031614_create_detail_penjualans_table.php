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
        Schema::create('detail_penjualans', function (Blueprint $table) {
            $table->id('detail_id');
            $table->unsignedBigInteger('kode_penjualan')->nullable();
            $table->unsignedBigInteger('produk_id')->nullable();
            $table->decimal('subtotal',10,2)->nullable();
            $table->unsignedBigInteger('jumlah_produk')->nullable();
            $table->unsignedBigInteger('pelanggan_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_penjualans');
    }
};
