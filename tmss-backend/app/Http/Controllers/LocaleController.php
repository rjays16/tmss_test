<?php

namespace App\Http\Controllers;

use App\Models\Locale;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LocaleController extends Controller
{
    public function index(): JsonResponse
    {
        $locales = Locale::orderBy('order')->paginate(20);
        return response()->json($locales);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:locales,code',
            'name' => 'required|string|max:100',
            'native_name' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'order' => 'integer|min:0',
        ]);

        $locale = Locale::create($validated);

        return response()->json($locale, 201);
    }

    public function show(int $id): JsonResponse
    {
        $locale = Locale::findOrFail($id);
        return response()->json($locale);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $locale = Locale::findOrFail($id);

        $validated = $request->validate([
            'code' => 'sometimes|string|max:10|unique:locales,code,' . $id,
            'name' => 'sometimes|string|max:100',
            'native_name' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'order' => 'integer|min:0',
        ]);

        $locale->update($validated);

        return response()->json($locale);
    }

    public function destroy(int $id): JsonResponse
    {
        $locale = Locale::findOrFail($id);
        $locale->delete();

        return response()->json(['message' => 'Locale deleted successfully']);
    }
}
