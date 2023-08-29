<?php

namespace Database\Factories;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;

class TimeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'appointment_id' => Appointment::inRandomOrder()->first()->id,
            'time' =>  getRandomTime(),
            'status' => $this->faker->numberBetween(0, 1),
        ];
    }
}
