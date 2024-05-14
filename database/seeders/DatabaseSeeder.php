<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Event;
use App\Models\Follower;
use App\Models\Tag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        Event::factory(10)->create();
        Comment::factory(10)->create();
        Tag::factory(10)->create();
        Follower::factory(30)->create();

        $existingAdmin = User::where('email', 'admin@example.com')->exists();
        if (!$existingAdmin) {
            User::factory()->create([
                'username' => 'TestUser',
                'email' => 'admin@example.com',
            ]);
        }
    }
}
