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
            'password' => bcrypt('admin12345'),
            'role_id' => 1,
        ]);

        User::create([
            'name' => 'Kasir',
            'email' => 'kasir@pos.com',
            'password' => bcrypt('kasir12345'),
            'role_id' => 2,
        ]);

        User::create([
            'name' => 'Pimpinan',
            'email' => 'pimpinan@pos.com',
            'password' => bcrypt('pimpinan12345'),
            'role_id' => 3,
        ]);
    }
}
