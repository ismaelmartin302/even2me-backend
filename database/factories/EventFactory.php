<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $users_ids = User::all()->pluck('id')->toArray();
        $capacity = fake()->randomFloat(0, 0, 250);
        $current_attendees = fake()->randomFloat(0, 0, $capacity);
        $starts_at = fake()->dateTimeBetween('-1 month', '+2 months');
        $finish_in = fake()->dateTimeInInterval($starts_at, '+' . fake()->randomFloat(0, 0, 48) . 'hours');
        return [
            'user_id' => fake()->randomElement($users_ids),
            'name' => fake()->sentence(2),
            'description' => fake()->text(),
            'location' => fake()->city(),
            'price' => fake()->randomFloat(2, 0, 10),
            'capacity' => $capacity,
            'current_attendees' => $current_attendees,
            'category' => fake()->word(),
            'picture' => fake()->imageUrl(),
            'website' => fake()->url(),
            'starts_at' =>  $starts_at,
            'finish_in' =>  $finish_in,
        ];
    }
}
