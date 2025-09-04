<?php

namespace App\Http\Controllers;

use App\Models\Dua;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DuaController extends Controller
{
    public function index(Request $request)
    {
        $query = Dua::with('user')->ordered();

        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        $duas = $query->get();
        $categories = Dua::getCategories();

        return Inertia::render('Duas/Index', [
            'duas' => $duas,
            'categories' => $categories,
            'currentCategory' => $request->category,
        ]);
    }
}
