<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CandidateFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->name;
        return [
            'event_id' => Event::factory(),
            'candidate_number' => sprintf('%02d', $this->faker->numberBetween(1, 20)),
            'name' => $name,
            'slug' => Str::slug($name) . '-' . Str::random(4),
            'profile_photo_path' => 'candidates/sample.jpg',
            'region' => $this->faker->city,
            'is_active' => true,
        ];
    }
}