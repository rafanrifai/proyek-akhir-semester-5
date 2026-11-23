<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat Unit Utama
        $unit = Unit::create([
            'name' => 'Kantor Pusat',
            'code' => 'KP-001',
            'type' => 'UTAMA',
            'parent_id' => null,
        ]);

        // Buat Akun Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@simbmn.com',
            'password' => Hash::make('password123'),
            'role' => 'ADMIN',
            'unit_id' => $unit->id,
        ]);
    }
}
