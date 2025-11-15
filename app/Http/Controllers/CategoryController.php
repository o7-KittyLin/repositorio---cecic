<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $category = Category::create(['name' => $request->name]);
        return redirect()->route('repository.index')->with('success', 'Categoria Registrada.');
    }
}

