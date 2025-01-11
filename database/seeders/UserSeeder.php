<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Riyen Perdana',
            'email' => 'riyenperdana@uin-suska.ac.id',
            'password' => bcrypt('password'),
            'nip' => '198111162011011010',
            'glr_blkg' => 'ST',
            'no_hp' => '082170237327',
            'jabatan_id' => 1,
            'unit_id' => 1,
            'is_aktif' => 'Y'
        ]);
    }
}
