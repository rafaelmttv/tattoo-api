<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        // Get roles
        $adminRole = \App\Models\Role::where('name', 'Admin')->first();
        $customerRole = \App\Models\Role::where('name', 'Customer')->first();
        $artistRole = \App\Models\Role::where('name', 'TattooArtist')->first();
        $studioRole = \App\Models\Role::where('name', 'Studio')->first();

        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $admin->roles()->attach($adminRole);

        // Create customer user
        $customer = User::factory()->create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'password' => bcrypt('password'),
        ]);
        $customer->roles()->attach($customerRole);

        // Create tattoo artist user
        $artist = User::factory()->create([
            'name' => 'Tattoo Artist User',
            'email' => 'artist@example.com',
            'password' => bcrypt('password'),
        ]);
        $artist->roles()->attach($artistRole);

        // Create studio user
        $studio = User::factory()->create([
            'name' => 'Studio User',
            'email' => 'studio@example.com',
            'password' => bcrypt('password'),
        ]);
        $studio->roles()->attach($studioRole);
    }
}
