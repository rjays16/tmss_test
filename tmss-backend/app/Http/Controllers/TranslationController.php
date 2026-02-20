<?php

namespace App\Http\Controllers;

use App\Models\Translation;
use App\Models\Locale;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
        $cdnEnabled = config('cdn.enabled', false);

        if (is_string($locales)) {
            $locales = explode(',', $locales);
        }

        // Create cache key based on request params
        $cacheKey = 'translations_export_' . md5(serialize($locales) . $tag);
        
        // Check cache first (cache for 5 minutes)
        if ($tag === '' && Cache::has($cacheKey)) {
            $result = Cache::get($cacheKey);
            return $this->buildExportResponse($result, $cdnEnabled);
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

        return $this->buildExportResponse($result, $cdnEnabled);
    }

    public function exportJsonCdn(Request $request): JsonResponse
    {
        $locales = $request->get('locales', ['en']);
        $tag = $request->get('tag', '');
        $version = $request->get('v', null);

        if (is_string($locales)) {
            $locales = explode(',', $locales);
        }

        $cdnCacheKey = 'cdn_translations_' . md5(serialize($locales) . $tag);
        $cdnTtl = config('cdn.cache_ttl', 3600);

        // Check CDN cache
        if ($tag === '' && Cache::has($cdnCacheKey)) {
            $result = Cache::get($cdnCacheKey);
            return $this->buildCdnResponse($result, $locales, $cdnTtl);
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

        $result = [
            '_metadata' => [
                'version' => $version ?? $this->generateVersionHash(),
                'generated_at' => now()->toIso8601String(),
                'locales' => $locales,
                'count' => $translations->count(),
            ],
            'translations' => [],
        ];

        foreach ($translations as $t) {
            $key = $t->key;
            if (!isset($result['translations'][$key])) {
                $result['translations'][$key] = [];
            }
            $result['translations'][$key][$t->locale] = $t->value;
            
            if ($tag === '' && $t->tags->isNotEmpty()) {
                $result['translations'][$key]['_tags'] = $t->tags->pluck('name')->toArray();
            }
        }

        // Cache for CDN (longer TTL)
        if ($tag === '') {
            Cache::put($cdnCacheKey, $result, $cdnTtl);
        }

        return $this->buildCdnResponse($result, $locales, $cdnTtl);
    }

    private function buildExportResponse(array $data, bool $cdnEnabled): JsonResponse
    {
        $response = response()->json($data)
            ->header('X-Content-Type-Options', 'test-backend-content');
        
        if ($cdnEnabled) {
            $response->header('Cache-Control', config('cdn.headers.cache_control', 'public, max-age=3600'))
                    ->header('Vary', config('cdn.headers.vary', 'Accept-Language'));
        }
        
        return $response;
    }

    private function buildCdnResponse(array $data, array $locales, int $ttl): JsonResponse
    {
        $cdnBaseUrl = config('cdn.base_url', '');
        
        $response = response()->json($data)
            ->header('Cache-Control', 'public, max-age=' . $ttl)
            ->header('Vary', 'Accept-Language')
            ->header('X-CDN-Cache', 'MISS')
            ->header('X-Content-Type-Options', 'test-backend-content');
        
        if (!empty($cdnBaseUrl)) {
            $response->header('X-CDN-Base-URL', $cdnBaseUrl);
        }
        
        return $response;
    }

    private function generateVersionHash(): string
    {
        $latestUpdate = Translation::max('updated_at');
        return md5($latestUpdate ?? 'no-translations');
    }
}
