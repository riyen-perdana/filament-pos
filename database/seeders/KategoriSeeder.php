<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kategori::create([
            'kategori_nama' => 'Contoh Kategori',
            'kategori_slug' => 'contoh-kategori',
            'is_aktif' => 'Y'
        ]);
    }
}
