<?php

namespace Tests\Unit;

use App\Models\Translation;
use App\Models\Locale;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TranslationModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_translation_belongs_to_locale(): void
    {
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

        $this->assertEquals('en', $translation->locale);
        $this->assertEquals($locale->id, $translation->locale_id);
    }

    public function test_translation_can_have_tags(): void
    {
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

        $tag = Tag::create([
            'name' => 'web',
            'color' => '#3B82F6'
        ]);

        $translation->tags()->attach($tag->id);

        $this->assertTrue($translation->tags->contains($tag));
    }

    public function test_translation_fillable_attributes(): void
    {
        $translation = new Translation([
            'key' => 'test.key',
            'value' => 'Test Value',
            'locale' => 'en',
            'locale_id' => 1
        ]);

        $this->assertEquals('test.key', $translation->key);
        $this->assertEquals('Test Value', $translation->value);
        $this->assertEquals('en', $translation->locale);
    }
}
