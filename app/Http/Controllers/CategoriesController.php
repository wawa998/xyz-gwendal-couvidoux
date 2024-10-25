<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Code;
use App\Models\Track;
use Illuminate\Contracts\View\View;

class CategoriesController extends Controller
{
    /**
     * Terms form.
     */
    public function show(int $id): View
    {
        return view('app.categories.show', [
            'category'=>Category::findById($id),
            'tracks'=>Track::getTracksByCategoryId($id)
        ]);
    }

    public function index(): View
    {
        return view('app.categories.index', [
            'categories'=>Category::getAllCategories(),
        ]);
    }
}
