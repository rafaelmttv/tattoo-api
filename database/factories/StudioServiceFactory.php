<?php

namespace Database\Factories;

use App\Models\Studio;
use App\Models\StudioService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudioService>
 */
class StudioServiceFactory extends Factory
{
    protected $model = StudioService::class;

    public function definition(): array
    {
        $services = [
            'Walk-In Tattoo Session',
            'Private Tattoo Room Booking',
            'Piercing Service',
            'Tattoo Removal Consultation',
            'Group Booking (4+ people)',
            'Aftercare Kit',
            'Custom Merchandise',
            'Tattoo Party Package',
            'Guest Artist Session',
        ];

        return [
            'provider_id' => Studio::factory(),
            'name' => fake()->randomElement($services),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 30, 2000),
            'duration' => fake()->randomElement([30, 60, 90, 120, 180, 240]),
            'active' => true,
        ];
    }
}
