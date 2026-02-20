<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Index on translation_tag for faster tag lookups
        Schema::table('translation_tag', function (Blueprint $table) {
            $table->index('translation_id');
            $table->index('tag_id');
        });

        // Index on locales (if not exists)
        Schema::table('locales', function (Blueprint $table) {
            if (!$this->indexExists('locales', 'locales_code_index')) {
                $table->index('code');
            }
            if (!$this->indexExists('locales', 'locales_is_active_index')) {
                $table->index('is_active');
            }
        });

        // Index on tags (if not exists)
        Schema::table('tags', function (Blueprint $table) {
            if (!$this->indexExists('tags', 'tags_name_index')) {
                $table->index('name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('translation_tag', function (Blueprint $table) {
            $table->dropIndex(['translation_id']);
            $table->dropIndex(['tag_id']);
        });

        Schema::table('locales', function (Blueprint $table) {
            $table->dropIndex(['code']);
            $table->dropIndex(['is_active']);
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });
    }

    private function indexExists(string $table, string $indexName): bool
    {
        return DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]) !== [];
    }
};
