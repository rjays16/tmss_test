<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'web', 'color' => '#3B82F6'],
            ['name' => 'mobile', 'color' => '#10B981'],
            ['name' => 'desktop', 'color' => '#8B5CF6'],
            ['name' => 'auth', 'color' => '#F59E0B'],
            ['name' => 'home', 'color' => '#EF4444'],
            ['name' => 'common', 'color' => '#6B7280'],
            ['name' => 'footer', 'color' => '#EC4899'],
            ['name' => 'header', 'color' => '#14B8A6'],
            ['name' => 'button', 'color' => '#F97316'],
            ['name' => 'form', 'color' => '#6366F1'],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }
    }
}
