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
    
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Verifica si tiene documentos asociados
        if ($category->documents()->count() > 0) {
            return back()->with('error', 'No puedes eliminar una categoría con documentos asignados.');
        }

        $category->delete();

        return back()->with('success', 'Categoría eliminada correctamente.');
    }

}

