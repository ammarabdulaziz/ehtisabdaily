<?php

namespace App\Http\Controllers;

use App\Models\Dua;
use App\Services\DuaCacheService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DuaController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        $cacheService = app(DuaCacheService::class);
        
        // Get duas from cache
        if ($request->filled('category')) {
            $duas = $cacheService->getDuasByCategory($request->category);
        } else {
            $duas = $cacheService->getAllDuasForUser();
        }

        // Get categories from cache
        $cachedCategories = $cacheService->getCategoriesForUser();
        $categories = collect($cachedCategories)
            ->mapWithKeys(fn($category) => [$category => $category])
            ->toArray();

        return Inertia::render('Duas/Index', [
            'duas' => $duas,
            'categories' => $categories,
            'currentCategory' => $request->category,
        ]);
    }
}
