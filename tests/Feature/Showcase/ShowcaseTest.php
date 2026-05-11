<?php

namespace Tests\Feature\Showcase;

use App\Models\Artwork;
use App\Models\Studio;
use App\Models\StudioService;
use App\Models\TattooArtist;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowcaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_showcase_studios_is_publicly_accessible(): void
    {
        Studio::factory(3)->create();

        $response = $this->getJson('/api/showcase/studios');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'slug', 'address', 'city', 'state', 'description'],
                ],
                'meta',
            ]);
    }

    public function test_showcase_studios_can_be_filtered_by_city(): void
    {
        Studio::factory(2)->create(['city' => 'São Paulo']);
        Studio::factory(1)->create(['city' => 'Rio de Janeiro']);

        $response = $this->getJson('/api/showcase/studios?city=São Paulo');

        $response->assertStatus(200);
        $this->assertCount(2, $response->json('data'));
    }

    public function test_showcase_studios_can_be_filtered_by_search(): void
    {
        Studio::factory()->create(['name' => 'Ink Master Studio']);
        Studio::factory()->create(['name' => 'Dark Arts Tattoo']);

        $response = $this->getJson('/api/showcase/studios?search=Ink Master');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
    }

    public function test_showcase_show_studio_returns_full_details(): void
    {
        $studio = Studio::factory()->create();
        StudioService::factory(2)->create(['provider_id' => $studio->id]);

        $response = $this->getJson("/api/showcase/studios/{$studio->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'services', 'contacts'],
            ]);
    }

    public function test_showcase_studio_portfolio_returns_artworks(): void
    {
        $studio = Studio::factory()->create();
        $artist = TattooArtist::factory()->create();
        $studio->tattooArtists()->attach($artist);
        Artwork::factory(3)->create(['creator_id' => $artist->id, 'active' => true]);
        Artwork::factory(1)->create(['creator_id' => $artist->id, 'active' => false]);

        $response = $this->getJson("/api/showcase/studios/{$studio->id}/portfolio");

        $response->assertStatus(200);
        $this->assertCount(3, $response->json('data'));
    }

    public function test_showcase_artists_is_publicly_accessible(): void
    {
        TattooArtist::factory(3)->create();

        $response = $this->getJson('/api/showcase/artists');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'bio', 'experience_years', 'specialties'],
                ],
            ]);
    }

    public function test_showcase_show_artist_returns_full_details(): void
    {
        $artist = TattooArtist::factory()->create();
        Artwork::factory(5)->create(['creator_id' => $artist->id, 'active' => true]);

        $response = $this->getJson("/api/showcase/artists/{$artist->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'bio', 'artworks', 'services'],
            ]);
    }

    public function test_showcase_artist_portfolio_can_be_filtered(): void
    {
        $artist = TattooArtist::factory()->create();
        Artwork::factory(3)->create(['creator_id' => $artist->id, 'style' => 'realism', 'active' => true]);
        Artwork::factory(2)->create(['creator_id' => $artist->id, 'style' => 'blackwork', 'active' => true]);

        $response = $this->getJson("/api/showcase/artists/{$artist->id}/portfolio?style=realism");

        $response->assertStatus(200);
        $this->assertCount(3, $response->json('data'));
    }

    public function test_showcase_artworks_is_publicly_accessible(): void
    {
        Artwork::factory(5)->create(['active' => true]);

        $response = $this->getJson('/api/showcase/artworks');

        $response->assertStatus(200);
        $this->assertCount(5, $response->json('data'));
    }

    public function test_showcase_search_requires_query(): void
    {
        $response = $this->getJson('/api/showcase/search');

        $response->assertStatus(422);
    }

    public function test_showcase_search_returns_results(): void
    {
        Studio::factory()->create(['name' => 'Ink Master Studio']);
        $artist = TattooArtist::factory()->create();
        User::find($artist->user_id)->update(['name' => 'Ink Artist']);
        Artwork::factory()->create(['name' => 'Ink Dragon', 'active' => true]);

        $response = $this->getJson('/api/showcase/search?q=Ink');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'studios',
                    'artists',
                    'artworks',
                ],
            ]);
    }
}
