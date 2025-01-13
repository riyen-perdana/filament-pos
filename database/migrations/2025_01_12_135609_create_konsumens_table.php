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
        Schema::create('konsumen', function (Blueprint $table) {
            $table->id();
            $table->string('konsumen_card')->unique();
            $table->string('konsumen_nama');
            $table->string('konsumen_email')->unique();
            $table->string('konsumen_no_hp');
            $table->string('konsumen_alamat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konsumen');
    }
};
