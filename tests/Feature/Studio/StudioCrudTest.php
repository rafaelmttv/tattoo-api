<?php

namespace Tests\Feature\Studio;

use App\Models\Studio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudioCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_studio(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/studios', [
            'name' => 'My Studio',
            'address' => 'Rua Teste, 123',
            'description' => 'A great studio.',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'My Studio');

        $this->assertDatabaseHas('studios', [
            'name' => 'My Studio',
            'user_id' => $user->id,
        ]);
    }

    public function test_unauthenticated_user_cannot_create_studio(): void
    {
        $response = $this->postJson('/api/studios', [
            'name' => 'My Studio',
        ]);

        $response->assertStatus(401);
    }

    public function test_studio_list_is_paginated(): void
    {
        $user = User::factory()->create();
        Studio::factory(20)->create();

        $response = $this->actingAs($user)->getJson('/api/studios');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'links',
                'meta',
            ]);
    }

    public function test_user_can_view_single_studio(): void
    {
        $user = User::factory()->create();
        $studio = Studio::factory()->create();

        $response = $this->actingAs($user)->getJson("/api/studios/{$studio->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $studio->id);
    }

    public function test_owner_can_update_studio(): void
    {
        $user = User::factory()->create();
        $studio = Studio::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->patchJson("/api/studios/{$studio->id}", [
            'name' => 'Updated Name',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Updated Name');
    }

    public function test_non_owner_cannot_update_studio(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $studio = Studio::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($otherUser)->patchJson("/api/studios/{$studio->id}", [
            'name' => 'Hacked Name',
        ]);

        $response->assertStatus(403);
    }

    public function test_owner_can_delete_studio(): void
    {
        $user = User::factory()->create();
        $studio = Studio::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->deleteJson("/api/studios/{$studio->id}");

        $response->assertStatus(204);
        $this->assertSoftDeleted('studios', ['id' => $studio->id]);
    }

    public function test_non_owner_cannot_delete_studio(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $studio = Studio::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($otherUser)->deleteJson("/api/studios/{$studio->id}");

        $response->assertStatus(403);
    }
}
