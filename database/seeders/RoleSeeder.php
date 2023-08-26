<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
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
                    'name' => 'dcotor',
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
    }
}
