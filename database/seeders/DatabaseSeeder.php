<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(3)->create();
        // $this->call([
        //     MonevSeeder::class,
        // ]);
        User::create([
            'name' => 'Admin Satu',
            'username' => 'admin',
            'role' => 'desa',
            'nohp' => '08123456789',
            'desa' => 'Desa Cipacing',
            'email' => 'admin1@example.com',
            'password' => Hash::make('123'),
        ]);

        User::create([
            'name' => 'Admin Dua',
            'username' => 'admin2',
            'role' => 'desa',
            'nohp' => '082233445566',
            'desa' => 'Desa Cileunyi',
            'email' => 'admin2@example.com',
            'password' => Hash::make('123'),
        ]);
    }
}
