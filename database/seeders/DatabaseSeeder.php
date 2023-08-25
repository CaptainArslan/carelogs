<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        Role::create(['name' => 'doctor']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'patient']);

        $admin = new User([
            "name" => 'Admin',
            "email" => 'admin@gmail.com',
            "email_verified_at" => now(),
            'password' => Hash::make('password'),
            'role_id' => 2,
            'gender' => 'male',
            'remember_token' => Str::random('10'),
        ]);

        $admin->save();
    }
}
