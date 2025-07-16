<?php

namespace Database\Factories;

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
        return [
            'name' => fake()->sentence(3),
            'event_group' => fake()->randomElement(['Conference', 'Workshop', 'Meetup', 'Webinar', 'Training']),
            'attrs' => [
                'location' => fake()->address(),
                'capacity' => fake()->numberBetween(10, 500),
                'description' => fake()->paragraph(),
                'tags' => fake()->words(3),
            ],
        ];
    }
}
