<?php

namespace App\Http\Controllers;

use App\Models\Translation;
use App\Models\Locale;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TranslationController extends Controller
{
    public function index(): JsonResponse
    {
        $translations = Translation::with(['locale', 'tags'])->paginate(20);
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

        $translation->load(['locale', 'tags']);

        return response()->json($translation);
    }

    public function destroy(int $id): JsonResponse
    {
        $translation = Translation::findOrFail($id);
        $translation->delete();

        return response()->json(['message' => 'Translation deleted successfully']);
    }

    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        $tag = $request->get('tag', '');
        $locale = $request->get('locale', '');

        $translations = Translation::with(['locale', 'tags'])
            ->when($query, function ($q) use ($query) {
                $q->where('key', 'like', "%{$query}%")
                  ->orWhere('value', 'like', "%{$query}%");
            })
            ->when($locale, function ($q) use ($locale) {
                $q->where('locale', $locale);
            })
            ->when($tag, function ($q) use ($tag) {
                $q->whereHas('tags', function ($t) use ($tag) {
                    $t->where('name', $tag);
                });
            })
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

        $translations = Translation::with('tags')
            ->whereIn('locale', $locales)
            ->when($tag, function ($q) use ($tag) {
                $q->whereHas('tags', function ($t) use ($tag) {
                    $t->where('name', $tag);
                });
            })
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

        return response()->json($result);
    }
}
