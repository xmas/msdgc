<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'remember_token' => Str::random(10),
            'profile_photo_path' => null,
            'current_team_id' => null,
            'sms' => fake()->optional()->phoneNumber(),
            'provider' => fake()->optional()->randomElement(['Verizon', 'AT&T', 'T-Mobile', 'Sprint', 'Other']),
            'sms_opt_in' => fake()->boolean(),
            'email_opt_in' => fake()->boolean(),
            'how_did_you_hear' => fake()->optional()->randomElement([
                'Google Search',
                'Facebook',
                'Friend Referral',
                'Golf Course',
                'Tournament',
                'Word of Mouth',
                'Other'
            ]),
            'paid_via' => fake()->optional()->randomElement([
                'Cash',
                'Check',
                'Credit Card',
                'PayPal',
                'Venmo',
                'Zelle'
            ]),
            'tags' => fake()->optional()->randomElements([
                'beginner',
                'intermediate',
                'advanced',
                'tournament_player',
                'casual_player',
                'instructor',
                'volunteer'
            ], fake()->numberBetween(0, 3)),
            'comments' => fake()->optional()->text(500),
            'topics' => fake()->optional()->text(1000),
            'region' => fake()->optional()->randomElement([
                'North',
                'South',
                'East',
                'West',
                'Central',
                'Northeast',
                'Southeast',
                'Southwest',
                'Northwest'
            ]),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user should have a personal team.
     */
    public function withPersonalTeam(?callable $callback = null): static
    {
        if (! Features::hasTeamFeatures()) {
            return $this->state([]);
        }

        return $this->has(
            Team::factory()
                ->state(fn (array $attributes, User $user) => [
                    'name' => $user->first_name.' '.$user->last_name.'\'s Team',
                    'user_id' => $user->id,
                    'personal_team' => true,
                ])
                ->when(is_callable($callback), $callback),
            'ownedTeams'
        );
    }
}
