<?php

namespace Database\Seeders;

use App\Models\Locale;
use Illuminate\Database\Seeder;

class LocaleSeeder extends Seeder
{
    public function run(): void
    {
        $locales = [
            ['code' => 'en', 'name' => 'English', 'native_name' => 'English', 'order' => 1],
            ['code' => 'fr', 'name' => 'French', 'native_name' => 'Français', 'order' => 2],
            ['code' => 'es', 'name' => 'Spanish', 'native_name' => 'Español', 'order' => 3],
            ['code' => 'de', 'name' => 'German', 'native_name' => 'Deutsch', 'order' => 4],
            ['code' => 'it', 'name' => 'Italian', 'native_name' => 'Italiano', 'order' => 5],
            ['code' => 'pt', 'name' => 'Portuguese', 'native_name' => 'Português', 'order' => 6],
            ['code' => 'zh', 'name' => 'Chinese', 'native_name' => '中文', 'order' => 7],
            ['code' => 'ja', 'name' => 'Japanese', 'native_name' => '日本語', 'order' => 8],
            ['code' => 'ko', 'name' => 'Korean', 'native_name' => '한국어', 'order' => 9],
            ['code' => 'ar', 'name' => 'Arabic', 'native_name' => 'العربية', 'order' => 10],
        ];

        foreach ($locales as $locale) {
            Locale::create($locale);
        }
    }
}
