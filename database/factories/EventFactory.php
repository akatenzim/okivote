<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->sentence(3);
        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . Str::random(4),
            'organizer_name' => $this->faker->company,
            'organizer_contact' => $this->faker->phoneNumber,
            'description' => $this->faker->paragraph,
            'vote_price' => 2000,
            'voting_start_at' => now()->subDay(),
            'voting_end_at' => now()->addDays(7),
            'status' => 'ONGOING',
            'published_at' => now(),
        ];
    }
}