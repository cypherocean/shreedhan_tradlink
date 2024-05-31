<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{

    public function run()
    {
        User::create([
            'name' => 'Super Dhilo',
            'email' => 'superadmin@mail.com',
            'password' => bcrypt('Admin@123'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
