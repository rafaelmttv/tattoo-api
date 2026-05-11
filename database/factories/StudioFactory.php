<?php

namespace Database\Factories;

use App\Models\Studio;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Studio>
 */
class StudioFactory extends Factory
{
    protected $model = Studio::class;

    public function definition(): array
    {
        $name = fake()->company() . ' Tattoo Studio';

        return [
            'user_id' => User::factory(),
            'name' => $name,
            'slug' => Str::slug($name) . '-' . fake()->unique()->randomNumber(4),
            'address' => fake()->address(),
            'city' => fake()->city(),
            'state' => fake()->stateAbbr(),
            'description' => fake()->paragraph(3),
            'logo_url' => null,
            'cover_url' => null,
            'featured' => fake()->boolean(20),
        ];
    }

    /**
     * Mark the studio as featured.
     */
    public function featured(): static
    {
        return $this->state(fn () => ['featured' => true]);
    }
}
