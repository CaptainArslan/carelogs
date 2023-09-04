<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $startDate = Carbon::now()->addDays($this->faker->numberBetween(0, 2));
        return [
            // 'id' => Str::uuid()->toString(),
            'user_id' => User::where('role_id', Role::PATIENT)->get()->random()->id,
            'doctor_id' =>  User::where('role_id', Role::DOCTOR)->get()->random()->id,
            'time' =>  getRandomTime(),
            'date' => $startDate->format('m-d-Y'),
            'status' => $this->faker->numberBetween(0, 1)
        ];
    }
}
