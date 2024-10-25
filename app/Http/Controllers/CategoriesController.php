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
    public function show(Category $category): View
    {
        return view('app.categories.show', [
            'category'=>$category,
            'tracks'=>Track::getTracksByCategoryId($category->id)
        ]);
    }
}
