<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition(): array
    {
        $types = [
            'phone' => fn () => fake()->phoneNumber(),
            'email' => fn () => fake()->safeEmail(),
            'instagram' => fn () => '@' . fake()->userName(),
            'whatsapp' => fn () => fake()->phoneNumber(),
            'website' => fn () => fake()->url(),
        ];

        $type = fake()->randomElement(array_keys($types));

        return [
            'contactable_type' => 'App\Models\Studio',
            'contactable_id' => 1,
            'type' => $type,
            'value' => $types[$type](),
        ];
    }
}
