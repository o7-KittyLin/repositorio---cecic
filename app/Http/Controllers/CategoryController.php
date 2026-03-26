<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
        ], [
            'name.required' => 'El nombre de la categoria es obligatorio.',
            'name.string' => 'El nombre debe ser texto valido.',
            'name.max' => 'El nombre no puede superar 100 caracteres.',
            'name.unique' => 'Esa categoria ya existe.',
        ]);

        Category::create(['name' => $validated['name']]);

        return redirect()->route('repository.index')
            ->with('success', 'Categoria registrada.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Verifica si tiene documentos asociados
        if ($category->documents()->count() > 0) {
            return back()->with('error', 'No puedes eliminar una categoria con documentos asignados.');
        }

        $category->delete();

        return back()->with('success', 'Categoria eliminada correctamente.');
    }
}
