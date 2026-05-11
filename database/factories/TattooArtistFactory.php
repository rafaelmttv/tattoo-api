<?php

namespace Database\Factories;

use App\Models\TattooArtist;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TattooArtist>
 */
class TattooArtistFactory extends Factory
{
    protected $model = TattooArtist::class;

    public function definition(): array
    {
        $specialties = ['traditional', 'neo-traditional', 'blackwork', 'realism', 'watercolor', 'japanese', 'tribal', 'geometric', 'minimalist', 'dotwork'];

        return [
            'user_id' => User::factory(),
            'slug' => fake()->unique()->slug(2),
            'bio' => fake()->paragraph(2),
            'experience_years' => fake()->numberBetween(1, 25),
            'specialties' => fake()->randomElements($specialties, fake()->numberBetween(1, 3)),
            'avatar_url' => null,
        ];
    }
}
