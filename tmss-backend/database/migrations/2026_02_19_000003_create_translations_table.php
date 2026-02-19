<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('key', 255)->index();
            $table->text('value')->nullable();
            $table->string('locale', 10);
            $table->foreignId('locale_id')->constrained('locales')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['key', 'locale']);
            $table->index(['locale', 'locale_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
