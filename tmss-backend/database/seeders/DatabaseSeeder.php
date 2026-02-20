<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LocaleSeeder::class,
            TagSeeder::class,
        ]);

        $useMassive = env('SEED_MASSIVE', false);
        
        if ($useMassive) {
            $this->command->info('Running massive translation seeder (100k+)...');
            $this->call(MassiveTranslationSeeder::class);
        } else {
            $this->command->info('Running standard translation seeder...');
            $this->call(TranslationSeeder::class);
        }

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'api_token' => 'test_token_12345',
        ]);
    }
}
