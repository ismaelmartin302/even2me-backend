<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Follower>
 */
class FollowerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $users_ids = User::all()->pluck('id')->toArray();
        $follower_id = fake()->randomElement($users_ids);
        $users_ids = User::whereNot('id', $follower_id)->pluck('id')->toArray();
        $following_id = fake()->randomElement($users_ids);      // Esto abrá que optimizarlo seguro
        return [
            
            'follower_id' => $follower_id,
            'following_id' => $following_id,
        ];
    }
}
