<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Unit::create([
            'unit_nama' => 'Pusat Teknologi Informasi dan Pangkalan Data',
            'unit_slug' => 'pusat-teknologi-informasi-dan-pangkalan-data',
            'is_aktif' => 'Y'
        ]);
    }
}
