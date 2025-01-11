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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nip')->after('id')->unique()->nullable();
            $table->string('no_hp')->after('email')->nullable();
            $table->string('glr_dpn')->after('no_hp')->nullable();
            $table->string('glr_blkg')->after('glr_dpn')->nullable();
            $table->enum('is_aktif', ['Y', 'N'])->default('Y')->after('glr_blkg');
            $table->foreignId('jabatan_id')->constrained('jabatan')->after('is_aktif');
            $table->foreignId('unit_id')->constrained('unit')->after('jabatan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
