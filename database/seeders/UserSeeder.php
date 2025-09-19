<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@pos.com',
            'password' => 'admin123',
            'role' => 'admin',
        ]);

        User::create([
        'name' => 'Kasir Satu',
        'email' => 'kasir@pos.com',
        'password' => bcrypt('kasir123'),
        'role' => 'kasir',
        ]);

        User::create([
        'name' => 'Pimpinan',
        'email' => 'pimpinan@pos.com',
        'password' => bcrypt('pimpinan123'),
        'role' => 'pimpinan',
        ]);

    }
}
