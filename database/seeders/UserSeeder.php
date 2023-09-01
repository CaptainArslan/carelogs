<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {    
        User::create(
            [
                "name" => 'doctor',
                "email" => 'doctor@gmail.com',
                "email_verified_at" => now(),
                'password' => Hash::make('12345678'),
                'role_id' => Role::DOCTOR,
                'gender' => 'male',
                'status' => User::ACTIVE,
            ],
        );
        User::create(
            [
                "name" => 'patient',
                "email" => 'patient@gmail.com',
                "email_verified_at" => now(),
                'password' => Hash::make('12345678'),
                'role_id' => Role::PATIENT,
                'gender' => 'male',
                'status' => User::ACTIVE,
            ],
        );

        User::factory(30)->create();
    }
}
