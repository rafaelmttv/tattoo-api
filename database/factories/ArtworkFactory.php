<?php

namespace Database\Factories;

use App\Models\Artwork;
use App\Models\TattooArtist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Artwork>
 */
class ArtworkFactory extends Factory
{
    protected $model = Artwork::class;

    public function definition(): array
    {
        $styles = ['traditional', 'neo-traditional', 'blackwork', 'realism', 'watercolor', 'japanese', 'tribal', 'geometric', 'minimalist', 'dotwork', 'lettering', 'new-school'];
        $bodyLocations = ['arm', 'forearm', 'shoulder', 'back', 'chest', 'leg', 'thigh', 'calf', 'ankle', 'wrist', 'neck', 'ribs', 'hand', 'finger', 'foot'];

        return [
            'creator_id' => TattooArtist::factory(),
            'name' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'image_url' => fake()->imageUrl(640, 640, 'tattoo'),
            'body_location' => fake()->randomElement($bodyLocations),
            'style' => fake()->randomElement($styles),
            'tags' => fake()->randomElements(['colorful', 'black-and-grey', 'fine-line', 'bold', 'detailed', 'small', 'large', 'cover-up'], fake()->numberBetween(1, 3)),
            'price' => fake()->randomFloat(2, 100, 5000),
            'active' => true,
        ];
    }

    /**
     * Mark the artwork as inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn () => ['active' => false]);
    }
}
