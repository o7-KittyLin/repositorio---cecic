<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PDF;

class RepositoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();

        $query = Document::with('category');

        // Filtrar por categoría
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $documents = $query->latest()->paginate(10);

        return view('repository.index', compact('documents', 'categories'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'file'        => 'required|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
            'category_id' => 'nullable|exists:categories,id',
            'price'       => 'nullable|numeric|min:0',
        ]);

        $file = $request->file('file');

        $fileName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                       . '.' . $file->getClientOriginalExtension();

        // Guardar el archivo
        $file->move(storage_path('app/public/documents'), $fileName);
        $path = 'documents/' . $fileName;

        // Generar preview PDF si no es PDF
        $previewPath = null;

        if ($file->getClientOriginalExtension() !== 'pdf') {
            $previewPath = 'documents/' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.pdf';

            Storage::disk('public')->put($previewPath, PDF::loadView('repository.preview')
                ->output());
        }

        Document::create([
            'user_id'     => auth()->id(),
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'description' => $request->description,
            'file_path'   => $path,
            'preview_path'=> $previewPath,
            'price'       => $request->price,
            'is_free'     => $request->price ? false : true,
        ]);

        return back()->with('success', 'Documento subido correctamente.');
    }



    public function edit(Document $document)
    {
        $categories = Category::all();
        return view('repository.edit', compact('document', 'categories'));
    }



    public function update(Request $request, Document $document)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'price'       => 'nullable|numeric|min:0',
        ]);

        $document->update([
            'title'       => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'price'       => $request->is_free == '0' ? $request->price : null,
            'is_free'     => $request->is_free == '1',
        ]);

        return redirect()->route('repository.index')->with('success', 'Documento actualizado.');
    }



    public function destroy(Document $document)
    {
        Storage::disk('public')->delete([$document->file_path, $document->preview_path]);
        $document->delete();

        return back()->with('success', 'Documento eliminado.');
    }



    public function gallery(Request $request)
    {
        $query = Document::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $documents = $query->latest()->paginate(12);
        $categories = Category::all();

        return view('repository.gallery', compact('documents', 'categories'));
    }



    /**
     * DESCARGA SEGURA
     */
    public function download(Document $document)
    {
        $user = auth()->user();

        if ($document->is_free || $user->hasRole('Administrador')) {
            return $this->serveFile($document);
        }

        $purchased = $user->purchases()
            ->where('document_id', $document->id)
            ->exists();

        if ($purchased) {
            return $this->serveFile($document);
        }

        return back()->with('error', 'Este documento es de pago. Debes adquirirlo antes de descargarlo.');
    }


    private function serveFile(Document $document)
    {
        $path = storage_path('app/public/' . $document->file_path);

        if (!file_exists($path)) {
            return back()->with('error', 'El archivo no existe en el servidor.');
        }

        return response()->download($path, basename($path));
    }
}
