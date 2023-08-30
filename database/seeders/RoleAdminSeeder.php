<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::insert(
            [
                [
                    'name' => 'admin',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'doctor',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'patient',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]
        );

        User::create(
            [
                "name" => 'Admin',
                "email" => 'admin@gmail.com',
                "email_verified_at" => now(),
                'password' => Hash::make('12345678'),
                'role_id' => Role::ADMIN,
                'gender' => 'male',
                'status' => User::ACTIVE,
            ],
        );

    }
}
