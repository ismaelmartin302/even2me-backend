<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $users_ids = User::all()->pluck('id')->toArray();
        $events_ids = Event::all()->pluck('id')->toArray();
        $comments_ids = Comment::all()->pluck('id')->toArray();
        if (fake()->boolean(80)) {
            $comments_ids = [];
        }
        return [
            'user_id' => fake()->randomElement($users_ids),
            'event_id' => fake()->randomElement($events_ids),
            'parent_comment_id' => fake()->randomElement($comments_ids),
            'content' => fake()->sentence(),
        ];
    }
}
