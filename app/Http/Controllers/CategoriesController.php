<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Code;
use App\Models\Track;
use Illuminate\Contracts\View\View;

class CategoriesController extends Controller
{

    public function show(string $category): View
    {
        $categoryModel = Category::findById((int) $category);

        $tracks = Track::where('category_id', $categoryModel->id)
            ->withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->paginate(10);

        return view('app.categories.show', [
            'category' => $categoryModel,
            'tracks' => $tracks,
        ]);
    }

    public function index(): View
    {
        return view('app.categories.index', [
            'categories'=>Category::getAllCategories(),
        ]);
    }
}
