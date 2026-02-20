<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Index on translations.key for faster lookups
        Schema::table('translations', function (Blueprint $table) {
            $table->index('key');
            $table->index('locale');
            $table->index('locale_id');
        });

        // Index on translation_tag for faster tag lookups
        Schema::table('translation_tag', function (Blueprint $table) {
            $table->index('translation_id');
            $table->index('tag_id');
        });

        // Index on locales
        Schema::table('locales', function (Blueprint $table) {
            $table->index('code');
            $table->index('is_active');
        });

        // Index on tags
        Schema::table('tags', function (Blueprint $table) {
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::table('translations', function (Blueprint $table) {
            $table->dropIndex(['key']);
            $table->dropIndex(['locale']);
            $table->dropIndex(['locale_id']);
        });

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
};
