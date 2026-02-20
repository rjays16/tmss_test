<?php

namespace Tests\Feature;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    protected function getAuthToken(): string
    {
        $user = User::factory()->create([
            'api_token' => 'test_token_12345'
        ]);
        return 'test_token_12345';
    }

    public function test_can_get_tags(): void
    {
        $token = $this->getAuthToken();
        
        Tag::create([
            'name' => 'web',
            'color' => '#3B82F6'
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/tags');

        $response->assertStatus(200);
    }

    public function test_can_create_tag(): void
    {
        $token = $this->getAuthToken();

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/tags', [
                'name' => 'mobile',
                'color' => '#10B981'
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('tags', [
            'name' => 'mobile',
            'color' => '#10B981'
        ]);
    }

    public function test_can_update_tag(): void
    {
        $token = $this->getAuthToken();
        
        $tag = Tag::create([
            'name' => 'web',
            'color' => '#3B82F6'
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->putJson("/api/v1/tags/{$tag->id}", [
                'color' => '#FF0000'
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tags', [
            'name' => 'web',
            'color' => '#FF0000'
        ]);
    }

    public function test_can_delete_tag(): void
    {
        $token = $this->getAuthToken();
        
        $tag = Tag::create([
            'name' => 'web',
            'color' => '#3B82F6'
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->deleteJson("/api/v1/tags/{$tag->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('tags', [
            'id' => $tag->id
        ]);
    }
}
