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
        Schema::create('asset', function (Blueprint $table) {
            $table->id();
            $table->string('asset_kode')->unique();
            $table->string('asset_nama');
            $table->text('asset_deskripsi')->nullable();
            $table->foreignId('kategori_id')->constrained('kategori')->onDelete('cascade');
            $table->foreignId('unit_id')->constrained('unit')->onDelete('cascade');
            $table->bigInteger('asset_harga');
            $table->integer('asset_stok');
            $table->enum('jenis_asset',['Barang','Jasa'])->default('Barang');
            $table->enum('is_share',['Y','N'])->default('N');
            $table->enum('is_aktif', ['Y', 'N'])->default('Y');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        Schema::dropIfExists('asset');
    }
};
