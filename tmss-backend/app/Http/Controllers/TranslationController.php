<?php

namespace App\Http\Controllers;

use App\Models\Translation;
use App\Models\Locale;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class TranslationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 1000);
        
        // Cap at 10000 to prevent memory issues
        if ($perPage > 10000) {
            $perPage = 10000;
        }
        
        // Optimize: select only needed columns
        $translations = Translation::select(['id', 'key', 'value', 'locale', 'locale_id'])
            ->with(['locale:id,code,name', 'tags:id,name,color'])
            ->orderBy('key')
            ->paginate($perPage);
            
        return response()->json($translations);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255',
            'value' => 'nullable|string',
            'locale_id' => 'required|exists:locales,id',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:tags,id',
        ]);

        $locale = Locale::findOrFail($validated['locale_id']);

        $translation = Translation::create([
            'key' => $validated['key'],
            'value' => $validated['value'],
            'locale_id' => $validated['locale_id'],
            'locale' => $locale->code,
        ]);

        if (!empty($validated['tag_ids'])) {
            $translation->tags()->sync($validated['tag_ids']);
        }

        Cache::forget('translations_export');
        
        $translation->load(['locale', 'tags']);

        return response()->json($translation, 201);
    }

    public function show(int $id): JsonResponse
    {
        $translation = Translation::with(['locale', 'tags'])->findOrFail($id);
        return response()->json($translation);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $translation = Translation::findOrFail($id);

        $validated = $request->validate([
            'key' => 'sometimes|string|max:255',
            'value' => 'nullable|string',
            'locale_id' => 'sometimes|exists:locales,id',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:tags,id',
        ]);

        $updateData = [
            'key' => $validated['key'] ?? $translation->key,
            'value' => $validated['value'] ?? $translation->value,
        ];

        if (isset($validated['locale_id'])) {
            $locale = Locale::findOrFail($validated['locale_id']);
            $updateData['locale_id'] = $validated['locale_id'];
            $updateData['locale'] = $locale->code;
        }

        $translation->update($updateData);

        if (isset($validated['tag_ids'])) {
            $translation->tags()->sync($validated['tag_ids']);
        }

        Cache::forget('translations_export');
        
        $translation->load(['locale', 'tags']);

        return response()->json($translation);
    }

    public function destroy(int $id): JsonResponse
    {
        $translation = Translation::findOrFail($id);
        $translation->delete();
        
        Cache::forget('translations_export');

        return response()->json(['message' => 'Translation deleted successfully']);
    }

    public function search(Request $request): JsonResponse
    {
        $searchQuery = $request->get('q', '');
        $tag = $request->get('tag', '');
        $locale = $request->get('locale', '');

        $translations = Translation::select(['id', 'key', 'value', 'locale', 'locale_id'])
            ->with(['locale:id,code,name', 'tags:id,name,color'])
            ->when($searchQuery, function ($q) use ($searchQuery) {
                $q->where('key', 'like', "%{$searchQuery}%")
                  ->orWhere('value', 'like', "%{$searchQuery}%");
            })
            ->when($locale, function ($q) use ($locale) {
                $q->where('locale', $locale);
            })
            ->when($tag, function ($q) use ($tag) {
                $q->whereHas('tags', function ($t) use ($tag) {
                    $t->where('name', $tag);
                });
            })
            ->orderBy('key')
            ->paginate(20);

        return response()->json($translations);
    }

    public function exportJson(Request $request): JsonResponse
    {
        $locales = $request->get('locales', ['en']);
        $tag = $request->get('tag', '');

        if (is_string($locales)) {
            $locales = explode(',', $locales);
        }

        // Create cache key based on request params
        $cacheKey = 'translations_export_' . md5(serialize($locales) . $tag);
        
        // Check cache first (cache for 5 minutes)
        if ($tag === '' && Cache::has($cacheKey)) {
            return response()->json(Cache::get($cacheKey));
        }

        // Optimize: use select and avoid loading all relations
        $translations = Translation::select(['id', 'key', 'value', 'locale'])
            ->with(['tags:id,name'])
            ->whereIn('locale', $locales)
            ->when($tag, function ($q) use ($tag) {
                $q->whereHas('tags', function ($t) use ($tag) {
                    $t->where('name', $tag);
                });
            })
            ->orderBy('key')
            ->get();

        $result = [];
        foreach ($translations as $t) {
            $key = $t->key;
            if (!isset($result[$key])) {
                $result[$key] = [];
            }
            $result[$key][$t->locale] = $t->value;
            
            if ($tag === '' && $t->tags->isNotEmpty()) {
                $result[$key]['_tags'] = $t->tags->pluck('name')->toArray();
            }
        }

        // Cache the result for 5 minutes (only if no tag filter)
        if ($tag === '') {
            Cache::put($cacheKey, $result, 300);
        }

        return response()->json($result);
    }
}
