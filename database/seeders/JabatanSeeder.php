<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Jabatan;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Jabatan::create([
            'jabatan_nama' => 'Super Administrator',
            'jabatan_slug' => 'super-administrator',
            'is_aktif' => 'Y'
        ]);
    }
}
