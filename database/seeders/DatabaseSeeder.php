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
            'name' => 'Admin Desa Cileunyi Wetan',
            'username' => 'admin2',
            'role' => 'desa',
            'nohp' => '08123456789',
            'desa' => 'Desa Cileunyi Wetan',
            'email' => 'admin1@example.com',
            'password' => Hash::make('123'),
        ]);

        User::create([
            'name' => 'Admin Desa Cileunyi Kulon',
            'username' => 'admin3',
            'role' => 'desa',
            'nohp' => '082233445566',
            'desa' => 'Desa Cileunyi Kulon',
            'email' => 'admin2@example.com',
            'password' => Hash::make('123'),
        ]);

        User::create([
            'name' => 'Admin Kecamatan Cileunyi',
            'username' => 'admin',
            'role' => 'kecamatan',
            'nohp' => '082233445566',
            'desa' => 'Kecamatan Cileunyi',
            'email' => 'admin_kec@example.com',
            'password' => Hash::make('pass'),
        ]);
    }
}
