<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function myFavorites()
    {
        $favorites = auth()->user()
            ->favorites()
            ->with('document')
            ->latest()

            ->paginate(12);

        return view('favorites.index', compact('favorites'));
    }
}
