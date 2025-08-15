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
            'name' => 'Admin Kecamatan Soreang',
            'username' => 'kecamatan_soreang',
            'role' => 'kecamatan',
            'nohp' => '0812346789',
            'desa' => 'Kecamatan Soreang',
            'email' => 'kecamatan@example.com',
            'password' => Hash::make('12345678'),
        ]);
        User::create([
            'name' => 'Admin Desa Cingcin',
            'username' => 'desa_cingcin',
            'role' => 'desa',
            'nohp' => '08123456789',
            'desa' => 'Desa Cingcin',
            'email' => 'desa@example.com',
            'password' => Hash::make('12345678'),
        ]);
        User::create([
            'name' => 'Admin Desa Karamatmulya',
            'username' => 'desa_karamatmulya',
            'role' => 'desa',
            'nohp' => '08123456789',
            'desa' => 'Desa Karamatmulya',
            'email' => 'desa@example.com',
            'password' => Hash::make('12345678'),
        ]);
        User::create([
            'name' => 'Admin Desa Pamekaran',
            'username' => 'desa_pamekaran',
            'role' => 'desa',
            'nohp' => '08123456789',
            'desa' => 'Desa Pamekaran',
            'email' => 'desa@example.com',
            'password' => Hash::make('12345678'),
        ]);
        User::create([
            'name' => 'Admin Desa Panyirapan',
            'username' => 'desa_panyirapan',
            'role' => 'desa',
            'nohp' => '08123456789',
            'desa' => 'Desa Panyirapan',
            'email' => 'desa@example.com',
            'password' => Hash::make('12345678'),
        ]);
        User::create([
            'name' => 'Admin Desa Parungserab',
            'username' => 'desa_parungserab',
            'role' => 'desa',
            'nohp' => '08123456789',
            'desa' => 'Desa Parungserab',
            'email' => 'desa@example.com',
            'password' => Hash::make('12345678'),
        ]);
        User::create([
            'name' => 'Admin Desa Sadu',
            'username' => 'desa_sadu',
            'role' => 'desa',
            'nohp' => '08123456789',
            'desa' => 'Desa Sadu',
            'email' => 'desa@example.com',
            'password' => Hash::make('12345678'),
        ]);
        User::create([
            'name' => 'Admin Desa Sekarwangi',
            'username' => 'desa_sekarwangi',
            'role' => 'desa',
            'nohp' => '08123456789',
            'desa' => 'Desa Sekarwangi',
            'email' => 'desa@example.com',
            'password' => Hash::make('12345678'),
        ]);
        User::create([
            'name' => 'Admin Desa Soreang',
            'username' => 'desa_soreang',
            'role' => 'desa',
            'nohp' => '08123456789',
            'desa' => 'Desa Soreang',
            'email' => 'desa@example.com',
            'password' => Hash::make('12345678'),
        ]);
        User::create([
            'name' => 'Admin Desa Sukajadi',
            'username' => 'desa_sukajadi',
            'role' => 'desa',
            'nohp' => '08123456789',
            'desa' => 'Desa Sukajadi',
            'email' => 'desa@example.com',
            'password' => Hash::make('12345678'),
        ]);
        User::create([
            'name' => 'Admin Desa Sukanagara',
            'username' => 'desa_sukanagara',
            'role' => 'desa',
            'nohp' => '08123456789',
            'desa' => 'Desa Sukanagara',
            'email' => 'desa@example.com',
            'password' => Hash::make('12345678'),
        ]);
    }
}
