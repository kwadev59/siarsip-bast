<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Matikan pengecekan foreign key agar bisa truncate tabel users
        Schema::disableForeignKeyConstraints();
        
        User::truncate();

        // Buat Super Admin dengan Username
        User::create([
            'name' => 'Super Admin',
            'username' => 'admin',
            'email' => 'admin@arsip.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
