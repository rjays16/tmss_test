<?php

namespace Tests\Feature;

use App\Models\Translation;
use App\Models\Locale;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PerformanceTest extends TestCase
{
    use RefreshDatabase;

    protected function getAuthToken(): string
    {
        $user = User::factory()->create([
            'api_token' => 'test_token_perf'
        ]);
        return 'test_token_perf';
    }

    public function test_search_endpoint_performance(): void
    {
        $token = $this->getAuthToken();
        
        $locale = Locale::create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'is_active' => true,
            'order' => 1
        ]);

        for ($i = 0; $i < 1000; $i++) {
            Translation::create([
                'key' => 'test.key.' . $i,
                'value' => 'Test Value ' . $i,
                'locale' => 'en',
                'locale_id' => $locale->id
            ]);
        }

        $startTime = microtime(true);
        
        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/translations/search?q=test');
        
        $endTime = microtime(true);
        $duration = ($endTime - $startTime) * 1000;

        $response->assertStatus(200);
        
        $this->assertLessThan(200, $duration, "Search endpoint took {$duration}ms (should be < 200ms)");
        
        echo "\nSearch endpoint: {$duration}ms";
    }

    public function test_export_endpoint_performance(): void
    {
        $token = $this->getAuthToken();
        
        $startTime = microtime(true);
        
        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/translations/export/json?locales=en');
        
        $endTime = microtime(true);
        $duration = ($endTime - $startTime) * 1000;

        $response->assertStatus(200);
        
        $this->assertLessThan(500, $duration, "Export endpoint took {$duration}ms (should be < 500ms)");
        
        echo "\nExport endpoint: {$duration}ms";
    }

    public function test_list_endpoint_performance(): void
    {
        $token = $this->getAuthToken();
        
        $startTime = microtime(true);
        
        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/translations?page=1');
        
        $endTime = microtime(true);
        $duration = ($endTime - $startTime) * 1000;

        $response->assertStatus(200);
        
        $this->assertLessThan(200, $duration, "List endpoint took {$duration}ms (should be < 200ms)");
        
        echo "\nList endpoint: {$duration}ms";
    }

    public function test_show_endpoint_performance(): void
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
        
        $startTime = microtime(true);
        
        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson("/api/v1/translations/{$translation->id}");
        
        $endTime = microtime(true);
        $duration = ($endTime - $startTime) * 1000;

        $response->assertStatus(200);
        
        $this->assertLessThan(200, $duration, "Show endpoint took {$duration}ms (should be < 200ms)");
        
        echo "\nShow endpoint: {$duration}ms";
    }

    public function test_store_endpoint_performance(): void
    {
        $token = $this->getAuthToken();
        
        $locale = Locale::create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'is_active' => true,
            'order' => 1
        ]);

        $startTime = microtime(true);
        
        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/translations', [
                'key' => 'perf.test.key',
                'value' => 'Performance Test',
                'locale_id' => $locale->id
            ]);
        
        $endTime = microtime(true);
        $duration = ($endTime - $startTime) * 1000;

        $response->assertStatus(201);
        
        $this->assertLessThan(200, $duration, "Store endpoint took {$duration}ms (should be < 200ms)");
        
        echo "\nStore endpoint: {$duration}ms";
    }

    public function test_update_endpoint_performance(): void
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
        
        $startTime = microtime(true);
        
        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->putJson("/api/v1/translations/{$translation->id}", [
                'value' => 'Updated Value'
            ]);
        
        $endTime = microtime(true);
        $duration = ($endTime - $startTime) * 1000;

        $response->assertStatus(200);
        
        $this->assertLessThan(200, $duration, "Update endpoint took {$duration}ms (should be < 200ms)");
        
        echo "\nUpdate endpoint: {$duration}ms";
    }

    public function test_destroy_endpoint_performance(): void
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
        
        $startTime = microtime(true);
        
        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->deleteJson("/api/v1/translations/{$translation->id}");
        
        $endTime = microtime(true);
        $duration = ($endTime - $startTime) * 1000;

        $response->assertStatus(200);
        
        $this->assertLessThan(200, $duration, "Destroy endpoint took {$duration}ms (should be < 200ms)");
        
        echo "\nDestroy endpoint: {$duration}ms";
    }

    public function test_locales_endpoint_performance(): void
    {
        $token = $this->getAuthToken();
        
        $startTime = microtime(true);
        
        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/locales');
        
        $endTime = microtime(true);
        $duration = ($endTime - $startTime) * 1000;

        $response->assertStatus(200);
        
        $this->assertLessThan(200, $duration, "Locales endpoint took {$duration}ms (should be < 200ms)");
        
        echo "\nLocales endpoint: {$duration}ms";
    }

    public function test_tags_endpoint_performance(): void
    {
        $token = $this->getAuthToken();
        
        $startTime = microtime(true);
        
        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/tags');
        
        $endTime = microtime(true);
        $duration = ($endTime - $startTime) * 1000;

        $response->assertStatus(200);
        
        $this->assertLessThan(200, $duration, "Tags endpoint took {$duration}ms (should be < 200ms)");
        
        echo "\nTags endpoint: {$duration}ms";
    }
}
