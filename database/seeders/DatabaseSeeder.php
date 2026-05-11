<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\ArtistService;
use App\Models\Artwork;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\Role;
use App\Models\Studio;
use App\Models\StudioService;
use App\Models\TattooArtist;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with rich demonstration data.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        $adminRole = Role::where('name', RoleEnum::Admin->value)->first();
        $customerRole = Role::where('name', RoleEnum::Customer->value)->first();
        $artistRole = Role::where('name', RoleEnum::TattooArtist->value)->first();
        $studioRole = Role::where('name', RoleEnum::Studio->value)->first();

        // ──────────────────────────────────────────────
        // Admin User
        // ──────────────────────────────────────────────
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $admin->roles()->attach($adminRole);

        // ──────────────────────────────────────────────
        // Studio 1: Ink Master SP
        // ──────────────────────────────────────────────
        $studioUser1 = User::factory()->create([
            'name' => 'Carlos Ink',
            'email' => 'studio@example.com',
            'password' => bcrypt('password'),
        ]);
        $studioUser1->roles()->attach($studioRole);

        $studio1 = Studio::create([
            'user_id' => $studioUser1->id,
            'name' => 'Ink Master Studio',
            'slug' => 'ink-master-studio',
            'address' => 'Rua Augusta, 1500',
            'city' => 'São Paulo',
            'state' => 'SP',
            'description' => 'O melhor estúdio de tatuagem de São Paulo, com mais de 10 anos de experiência em diversos estilos.',
            'featured' => true,
        ]);

        // Studio contacts
        Contact::create(['contactable_type' => Studio::class, 'contactable_id' => $studio1->id, 'type' => 'phone', 'value' => '(11) 99999-0001']);
        Contact::create(['contactable_type' => Studio::class, 'contactable_id' => $studio1->id, 'type' => 'instagram', 'value' => '@inkmasterstudio']);
        Contact::create(['contactable_type' => Studio::class, 'contactable_id' => $studio1->id, 'type' => 'whatsapp', 'value' => '(11) 99999-0001']);

        // Studio services
        StudioService::create(['provider_id' => $studio1->id, 'name' => 'Walk-In Tattoo Session', 'description' => 'Sessão sem agendamento prévio.', 'price' => 200.00, 'duration' => 60, 'active' => true]);
        StudioService::create(['provider_id' => $studio1->id, 'name' => 'Private Room Booking', 'description' => 'Sala privativa para sessões exclusivas.', 'price' => 500.00, 'duration' => 180, 'active' => true]);
        StudioService::create(['provider_id' => $studio1->id, 'name' => 'Piercing Service', 'description' => 'Piercings profissionais com materiais esterilizados.', 'price' => 80.00, 'duration' => 30, 'active' => true]);

        // ──────────────────────────────────────────────
        // Studio 2: Dark Arts RJ
        // ──────────────────────────────────────────────
        $studioUser2 = User::factory()->create([
            'name' => 'Mariana Dark',
            'email' => 'studio2@example.com',
            'password' => bcrypt('password'),
        ]);
        $studioUser2->roles()->attach($studioRole);

        $studio2 = Studio::create([
            'user_id' => $studioUser2->id,
            'name' => 'Dark Arts Tattoo',
            'slug' => 'dark-arts-tattoo',
            'address' => 'Av. Atlântica, 800',
            'city' => 'Rio de Janeiro',
            'state' => 'RJ',
            'description' => 'Especialistas em blackwork e dotwork com artistas renomados.',
            'featured' => true,
        ]);

        Contact::create(['contactable_type' => Studio::class, 'contactable_id' => $studio2->id, 'type' => 'instagram', 'value' => '@darkartstattoo']);
        Contact::create(['contactable_type' => Studio::class, 'contactable_id' => $studio2->id, 'type' => 'email', 'value' => 'contato@darkarts.com']);

        StudioService::create(['provider_id' => $studio2->id, 'name' => 'Cover-Up Consultation', 'description' => 'Avaliação gratuita para cover-ups.', 'price' => 0.00, 'duration' => 30, 'active' => true]);
        StudioService::create(['provider_id' => $studio2->id, 'name' => 'Full Day Session', 'description' => 'Sessão dia inteiro com intervalo.', 'price' => 2500.00, 'duration' => 480, 'active' => true]);

        // ──────────────────────────────────────────────
        // Tattoo Artist 1 (linked to Studio 1)
        // ──────────────────────────────────────────────
        $artistUser1 = User::factory()->create([
            'name' => 'Rafael Tattoo',
            'email' => 'artist@example.com',
            'password' => bcrypt('password'),
        ]);
        $artistUser1->roles()->attach($artistRole);

        $artist1 = TattooArtist::create([
            'user_id' => $artistUser1->id,
            'slug' => 'rafael-tattoo',
            'bio' => 'Tatuador especialista em realismo e retratos com 8 anos de experiência.',
            'experience_years' => 8,
            'specialties' => ['realism', 'neo-traditional', 'watercolor'],
        ]);

        Contact::create(['contactable_type' => TattooArtist::class, 'contactable_id' => $artist1->id, 'type' => 'instagram', 'value' => '@rafaeltattoo']);
        $studio1->tattooArtists()->attach($artist1);

        // Artist 1 services
        ArtistService::create(['provider_id' => $artist1->id, 'name' => 'Custom Tattoo Design', 'description' => 'Design personalizado com revisões.', 'price' => 300.00, 'duration' => 120, 'active' => true]);
        ArtistService::create(['provider_id' => $artist1->id, 'name' => 'Portrait Tattoo', 'description' => 'Retratos realistas em preto e cinza ou colorido.', 'price' => 800.00, 'duration' => 240, 'active' => true]);
        ArtistService::create(['provider_id' => $artist1->id, 'name' => 'Touch-Up Session', 'description' => 'Retoque em tatuagens antigas.', 'price' => 150.00, 'duration' => 60, 'active' => true]);

        // Artist 1 artworks (portfolio)
        $artworkData1 = [
            ['name' => 'Lion Portrait', 'body_location' => 'arm', 'style' => 'realism', 'tags' => ['black-and-grey', 'detailed', 'large'], 'price' => 1200.00],
            ['name' => 'Rose & Skull', 'body_location' => 'forearm', 'style' => 'neo-traditional', 'tags' => ['colorful', 'bold'], 'price' => 800.00],
            ['name' => 'Watercolor Phoenix', 'body_location' => 'back', 'style' => 'watercolor', 'tags' => ['colorful', 'large', 'detailed'], 'price' => 2500.00],
            ['name' => 'Geometric Mandala', 'body_location' => 'shoulder', 'style' => 'geometric', 'tags' => ['black-and-grey', 'fine-line'], 'price' => 600.00],
            ['name' => 'Family Portrait', 'body_location' => 'chest', 'style' => 'realism', 'tags' => ['black-and-grey', 'detailed', 'large'], 'price' => 3000.00],
        ];

        foreach ($artworkData1 as $artwork) {
            Artwork::create(array_merge($artwork, [
                'creator_id' => $artist1->id,
                'description' => fake()->paragraph(),
                'image_url' => 'https://picsum.photos/seed/' . Str::slug($artwork['name']) . '/640/640',
                'active' => true,
            ]));
        }

        // ──────────────────────────────────────────────
        // Tattoo Artist 2 (linked to both studios)
        // ──────────────────────────────────────────────
        $artistUser2 = User::factory()->create([
            'name' => 'Julia Blackwork',
            'email' => 'artist2@example.com',
            'password' => bcrypt('password'),
        ]);
        $artistUser2->roles()->attach($artistRole);

        $artist2 = TattooArtist::create([
            'user_id' => $artistUser2->id,
            'slug' => 'julia-blackwork',
            'bio' => 'Especialista em blackwork e dotwork. Premiada em convenções internacionais.',
            'experience_years' => 12,
            'specialties' => ['blackwork', 'dotwork', 'geometric'],
        ]);

        Contact::create(['contactable_type' => TattooArtist::class, 'contactable_id' => $artist2->id, 'type' => 'instagram', 'value' => '@juliablackwork']);
        $studio1->tattooArtists()->attach($artist2);
        $studio2->tattooArtists()->attach($artist2);

        ArtistService::create(['provider_id' => $artist2->id, 'name' => 'Blackwork Full Sleeve', 'description' => 'Design completo de manga em blackwork.', 'price' => 5000.00, 'duration' => 480, 'active' => true]);
        ArtistService::create(['provider_id' => $artist2->id, 'name' => 'Dotwork Session', 'description' => 'Sessão de pontilhismo artístico.', 'price' => 400.00, 'duration' => 120, 'active' => true]);

        $artworkData2 = [
            ['name' => 'Sacred Geometry', 'body_location' => 'back', 'style' => 'geometric', 'tags' => ['black-and-grey', 'large', 'detailed'], 'price' => 3500.00],
            ['name' => 'Dotwork Mandala', 'body_location' => 'forearm', 'style' => 'dotwork', 'tags' => ['black-and-grey', 'fine-line'], 'price' => 900.00],
            ['name' => 'Blackwork Forest', 'body_location' => 'arm', 'style' => 'blackwork', 'tags' => ['bold', 'large'], 'price' => 1800.00],
        ];

        foreach ($artworkData2 as $artwork) {
            Artwork::create(array_merge($artwork, [
                'creator_id' => $artist2->id,
                'description' => fake()->paragraph(),
                'image_url' => 'https://picsum.photos/seed/' . Str::slug($artwork['name']) . '/640/640',
                'active' => true,
            ]));
        }

        // ──────────────────────────────────────────────
        // Tattoo Artist 3 (linked to Studio 2)
        // ──────────────────────────────────────────────
        $artistUser3 = User::factory()->create([
            'name' => 'Pedro Japanese',
            'email' => 'artist3@example.com',
            'password' => bcrypt('password'),
        ]);
        $artistUser3->roles()->attach($artistRole);

        $artist3 = TattooArtist::create([
            'user_id' => $artistUser3->id,
            'slug' => 'pedro-japanese',
            'bio' => 'Mestre em tatuagem japonesa tradicional (Irezumi). Estudou com mestres no Japão.',
            'experience_years' => 15,
            'specialties' => ['japanese', 'traditional'],
        ]);

        Contact::create(['contactable_type' => TattooArtist::class, 'contactable_id' => $artist3->id, 'type' => 'instagram', 'value' => '@pedrojapanese']);
        $studio2->tattooArtists()->attach($artist3);

        ArtistService::create(['provider_id' => $artist3->id, 'name' => 'Japanese Full Back', 'description' => 'Tatuagem japonesa completa nas costas.', 'price' => 15000.00, 'duration' => 2400, 'active' => true]);

        $artworkData3 = [
            ['name' => 'Koi Dragon', 'body_location' => 'back', 'style' => 'japanese', 'tags' => ['colorful', 'large', 'detailed'], 'price' => 8000.00],
            ['name' => 'Samurai Warrior', 'body_location' => 'arm', 'style' => 'japanese', 'tags' => ['colorful', 'bold', 'large'], 'price' => 4000.00],
            ['name' => 'Cherry Blossoms', 'body_location' => 'ribs', 'style' => 'japanese', 'tags' => ['colorful', 'fine-line'], 'price' => 1500.00],
            ['name' => 'Traditional Tiger', 'body_location' => 'thigh', 'style' => 'traditional', 'tags' => ['colorful', 'bold'], 'price' => 2000.00],
        ];

        foreach ($artworkData3 as $artwork) {
            Artwork::create(array_merge($artwork, [
                'creator_id' => $artist3->id,
                'description' => fake()->paragraph(),
                'image_url' => 'https://picsum.photos/seed/' . Str::slug($artwork['name']) . '/640/640',
                'active' => true,
            ]));
        }

        // ──────────────────────────────────────────────
        // Customer Users
        // ──────────────────────────────────────────────
        $customerUser = User::factory()->create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'password' => bcrypt('password'),
        ]);
        $customerUser->roles()->attach($customerRole);
        Customer::create(['user_id' => $customerUser->id, 'birth_date' => '1995-06-15']);

        // Additional random customers
        User::factory(3)->create()->each(function (User $user) use ($customerRole) {
            $user->roles()->attach($customerRole);
            Customer::create([
                'user_id' => $user->id,
                'birth_date' => fake()->dateTimeBetween('-50 years', '-18 years')->format('Y-m-d'),
            ]);
        });
    }
}
