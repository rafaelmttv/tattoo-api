<?php

namespace Tests\Feature\Artwork;

use App\Models\Artwork;
use App\Models\TattooArtist;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArtworkCrudTest extends TestCase
{
    use RefreshDatabase;

    private function createArtistUser(): array
    {
        $user = User::factory()->create();
        $artist = TattooArtist::factory()->create(['user_id' => $user->id]);

        return [$user, $artist];
    }

    public function test_public_can_list_active_artworks(): void
    {
        Artwork::factory(5)->create(['active' => true]);
        Artwork::factory(2)->create(['active' => false]);

        $response = $this->getJson('/api/artworks');

        $response->assertStatus(200);

        // All returned artworks should be active
        $data = $response->json('data');
        $this->assertCount(5, $data);
    }

    public function test_public_can_view_single_artwork(): void
    {
        $artwork = Artwork::factory()->create();

        $response = $this->getJson("/api/artworks/{$artwork->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $artwork->id);
    }

    public function test_artist_can_create_artwork(): void
    {
        [$user, $artist] = $this->createArtistUser();

        $response = $this->actingAs($user)->postJson('/api/artworks', [
            'name' => 'Dragon Sleeve',
            'description' => 'Full sleeve dragon tattoo',
            'body_location' => 'arm',
            'price' => 2000.00,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'Dragon Sleeve');

        $this->assertDatabaseHas('artworks', [
            'name' => 'Dragon Sleeve',
            'creator_id' => $artist->id,
        ]);
    }

    public function test_creator_can_update_own_artwork(): void
    {
        [$user, $artist] = $this->createArtistUser();
        $artwork = Artwork::factory()->create(['creator_id' => $artist->id]);

        $response = $this->actingAs($user)->patchJson("/api/artworks/{$artwork->id}", [
            'name' => 'Updated Dragon',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Updated Dragon');
    }

    public function test_non_creator_cannot_update_artwork(): void
    {
        [$user1, $artist1] = $this->createArtistUser();
        [$user2, $artist2] = $this->createArtistUser();

        $artwork = Artwork::factory()->create(['creator_id' => $artist1->id]);

        $response = $this->actingAs($user2)->patchJson("/api/artworks/{$artwork->id}", [
            'name' => 'Hacked',
        ]);

        $response->assertStatus(403);
    }

    public function test_artworks_can_be_filtered_by_body_location(): void
    {
        Artwork::factory(3)->create(['body_location' => 'arm', 'active' => true]);
        Artwork::factory(2)->create(['body_location' => 'back', 'active' => true]);

        $response = $this->getJson('/api/artworks?body_location=arm');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(3, $data);
    }
}
