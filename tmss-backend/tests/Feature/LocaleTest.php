<?php

namespace Tests\Feature;

use App\Models\Locale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocaleTest extends TestCase
{
    use RefreshDatabase;

    protected function getAuthToken(): string
    {
        $user = User::factory()->create([
            'api_token' => 'test_token_12345'
        ]);
        return 'test_token_12345';
    }

    public function test_can_get_locales(): void
    {
        $token = $this->getAuthToken();
        
        Locale::create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'is_active' => true,
            'order' => 1
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/locales');

        $response->assertStatus(200);
    }

    public function test_can_create_locale(): void
    {
        $token = $this->getAuthToken();

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/locales', [
                'code' => 'fr',
                'name' => 'French',
                'native_name' => 'Français',
                'is_active' => true,
                'order' => 2
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('locales', [
            'code' => 'fr',
            'name' => 'French'
        ]);
    }

    public function test_can_update_locale(): void
    {
        $token = $this->getAuthToken();
        
        $locale = Locale::create([
            'code' => 'fr',
            'name' => 'French',
            'native_name' => 'Français',
            'is_active' => true,
            'order' => 2
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->putJson("/api/v1/locales/{$locale->id}", [
                'name' => 'French Updated'
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('locales', [
            'code' => 'fr',
            'name' => 'French Updated'
        ]);
    }

    public function test_can_delete_locale(): void
    {
        $token = $this->getAuthToken();
        
        $locale = Locale::create([
            'code' => 'fr',
            'name' => 'French',
            'native_name' => 'Français',
            'is_active' => true,
            'order' => 2
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->deleteJson("/api/v1/locales/{$locale->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('locales', [
            'id' => $locale->id
        ]);
    }
}
