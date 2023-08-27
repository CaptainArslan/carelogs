<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $startDate = Carbon::now()->addDays($this->faker->numberBetween(0, 3));
        return [
            'user_id' => User::WhereRoleIsDoctor()->get()->random()->id,
            'date' => $startDate->format('m-d-Y'),
        ];
    }
}
