<?php

namespace Database\Factories;

use App\Models\ArtistService;
use App\Models\TattooArtist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ArtistService>
 */
class ArtistServiceFactory extends Factory
{
    protected $model = ArtistService::class;

    public function definition(): array
    {
        $services = [
            'Custom Tattoo Design',
            'Cover-Up Tattoo',
            'Touch-Up / Retouching',
            'Flash Tattoo',
            'Consultation Session',
            'Portrait Tattoo',
            'Sleeve Design',
            'Lettering / Script Tattoo',
            'Watercolor Tattoo',
            'Geometric Tattoo',
        ];

        return [
            'provider_id' => TattooArtist::factory(),
            'name' => fake()->randomElement($services),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 50, 3000),
            'duration' => fake()->randomElement([30, 60, 90, 120, 180, 240, 360]),
            'active' => true,
        ];
    }
}
