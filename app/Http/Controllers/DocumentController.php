<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{

    public function index()
    {
        $documents = Document::with('user')->latest()->paginate(10);
        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        return view('documents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
            'price' => 'nullable|numeric|min:0',
        ]);

        $file = $request->file('file');
        $fileName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
            . '.' . $file->getClientOriginalExtension();

        // 🔹 Guarda el archivo en storage/app/public/documents
        $file->move(storage_path('app/public/documents'), $fileName);
        $path = 'documents/' . $fileName;

        // 🔹 (Opcional) Generar vista previa PDF si no es PDF
        $previewPath = null;
        if ($file->getClientOriginalExtension() !== 'pdf') {
            $previewPath = 'documents/' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.pdf';
            Storage::disk('public')->put($previewPath, file_get_contents($file));
        }

        Document::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $path,
            'preview_path' => $previewPath,
            'price' => $request->price,
            'is_free' => $request->price ? false : true,
        ]);

        return redirect()->route('documents.index')->with('success', 'Documento cargado correctamente.');
    }

    public function download(Document $document)
    {
        $user = auth()->user();

        // Solo se descarga si es gratuito o si el usuario es Admin
        if ($document->is_free || ($user && $user->hasRole('Administrador'))) {
            $path = storage_path('app/public/' . $document->file_path);

            if (!file_exists($path)) {
                return back()->with('error', 'El archivo no existe en el servidor.');
            }

            return response()->download($path, basename($path));
        }

        if ($user && $document->isPurchasedBy($user)) {
            $path = storage_path('app/public/' . $document->file_path);
            if (!file_exists($path)) {
                return back()->with('error', 'El archivo no existe en el servidor.');
            }
            return response()->download($path, basename($path));
        }

        return back()->with('error', 'Este documento es de pago. Compra simulada no disponible.');
    }

    public function edit(Document $document)
    {
        return view('documents.edit', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
        ]);

        $document->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'is_free' => $request->price ? false : true,
        ]);

        return redirect()->route('documents.index')->with('success', 'Documento actualizado correctamente.');
    }

    public function destroy(Document $document)
    {
        Storage::disk('public')->delete([$document->file_path, $document->preview_path]);
        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Documento eliminado correctamente.');
    }

    public function show(Document $document)
    {
        $document->increment('views_count');

        $document->load(['user', 'category', 'comments.user', 'likes']);

        $isPurchased = auth()->check() ? $document->isPurchasedBy(auth()->user()) : false;

        $canViewFull =
            $document->is_free ||
            $isPurchased ||
            (auth()->check() && auth()->user()->hasRole('Administrador'));

        $relatedDocuments = Document::where('category_id', $document->category_id)
            ->where('id', '!=', $document->id)
            ->limit(4)
            ->get();

        return view('documents.show', compact(
            'document',
            'isPurchased',
            'canViewFull',
            'relatedDocuments'
        ));
    }


    /**
     * Incrementar vistas (para AJAX)
     */
    public function incrementViews(Document $document)
    {
        $document->increment('views_count');
        return response()->json(['success' => true, 'views_count' => $document->views_count]);
    }
}
