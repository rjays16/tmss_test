<?php

namespace Tests\Feature;

use App\Models\Translation;
use App\Models\Locale;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TranslationTest extends TestCase
{
    use RefreshDatabase;

    protected function getAuthToken(): string
    {
        $user = User::factory()->create([
            'api_token' => 'test_token_12345'
        ]);
        return 'test_token_12345';
    }

    public function test_can_get_translations(): void
    {
        $token = $this->getAuthToken();
        
        $locale = Locale::create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'is_active' => true,
            'order' => 1
        ]);

        Translation::create([
            'key' => 'test.key',
            'value' => 'Test Value',
            'locale' => 'en',
            'locale_id' => $locale->id
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/translations');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'key', 'value', 'locale']
            ]
        ]);
    }

    public function test_can_create_translation(): void
    {
        $token = $this->getAuthToken();
        
        $locale = Locale::create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'is_active' => true,
            'order' => 1
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/translations', [
                'key' => 'new.key',
                'value' => 'New Value',
                'locale_id' => $locale->id
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('translations', [
            'key' => 'new.key',
            'value' => 'New Value'
        ]);
    }

    public function test_can_update_translation(): void
    {
        $token = $this->getAuthToken();
        
        $locale = Locale::create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'is_active' => true,
            'order' => 1
        ]);

        $translation = Translation::create([
            'key' => 'test.key',
            'value' => 'Old Value',
            'locale' => 'en',
            'locale_id' => $locale->id
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->putJson("/api/v1/translations/{$translation->id}", [
                'value' => 'Updated Value'
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('translations', [
            'key' => 'test.key',
            'value' => 'Updated Value'
        ]);
    }

    public function test_can_delete_translation(): void
    {
        $token = $this->getAuthToken();
        
        $locale = Locale::create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'is_active' => true,
            'order' => 1
        ]);

        $translation = Translation::create([
            'key' => 'test.key',
            'value' => 'Test Value',
            'locale' => 'en',
            'locale_id' => $locale->id
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->deleteJson("/api/v1/translations/{$translation->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('translations', [
            'id' => $translation->id
        ]);
    }

    public function test_can_search_translations(): void
    {
        $token = $this->getAuthToken();
        
        $locale = Locale::create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'is_active' => true,
            'order' => 1
        ]);

        Translation::create([
            'key' => 'hello.world',
            'value' => 'Hello World',
            'locale' => 'en',
            'locale_id' => $locale->id
        ]);

        Translation::create([
            'key' => 'goodbye.world',
            'value' => 'Goodbye World',
            'locale' => 'en',
            'locale_id' => $locale->id
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/translations/search?q=hello');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'key', 'value', 'locale']
            ]
        ]);
    }

    public function test_can_export_translations(): void
    {
        $token = $this->getAuthToken();
        
        $locale = Locale::create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'is_active' => true,
            'order' => 1
        ]);

        Translation::create([
            'key' => 'test.key',
            'value' => 'Test Value',
            'locale' => 'en',
            'locale_id' => $locale->id
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/translations/export/json?locales=en');

        $response->assertStatus(200);
    }

    public function test_export_endpoint_has_cdn_headers(): void
    {
        $locale = Locale::create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'is_active' => true,
            'order' => 1
        ]);

        Translation::create([
            'key' => 'test.key',
            'value' => 'Test Value',
            'locale' => 'en',
            'locale_id' => $locale->id
        ]);

        $response = $this->getJson('/api/v1/translations/export/json?locales=en');

        $response->assertStatus(200);
        $response->assertHeader('X-Content-Type-Options', 'test-backend-content');
        $response->assertHeader('Vary');
        $response->assertHeader('Cache-Control');
    }

    public function test_cdn_endpoint_has_proper_headers(): void
    {
        $locale = Locale::create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'is_active' => true,
            'order' => 1
        ]);

        Translation::create([
            'key' => 'test.key',
            'value' => 'Test Value',
            'locale' => 'en',
            'locale_id' => $locale->id
        ]);

        $response = $this->getJson('/api/v1/translations/export/cdn?locales=en');

        $response->assertStatus(200);
        $response->assertHeader('X-Content-Type-Options', 'test-backend-content');
        $response->assertHeader('X-CDN-Cache');
        $response->assertHeader('Cache-Control');
    }

    public function test_requires_authentication(): void
    {
        $response = $this->getJson('/api/v1/translations');
        $response->assertStatus(401);
    }
}
