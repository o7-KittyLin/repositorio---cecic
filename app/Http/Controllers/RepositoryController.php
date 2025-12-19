<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Category;
use App\Models\PaymentSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RepositoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();

        $query = Document::with('category')->where('is_active', true);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $documents = $query->latest()->paginate(10);

        return view('repository.index', compact('documents', 'categories'));
    }



    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'file'        => 'required|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
            'category_id' => 'nullable|exists:categories,id',
            'price'       => 'nullable|numeric|min:0',
        ], [
            'title.required'       => 'El título es obligatorio.',
            'title.string'         => 'El título debe ser un texto válido.',
            'title.max'            => 'El título no puede superar los 255 caracteres.',
            'description.string'   => 'La descripción debe ser un texto válido.',
            'file.required'        => 'Debes subir un archivo.',
            'file.file'            => 'El archivo no es válido.',
            'file.mimes'           => 'El archivo debe ser PDF, DOC, DOCX, PPT o PPTX.',
            'file.max'             => 'El archivo es muy pesado. Máximo 10MB.',
            'category_id.exists'   => 'La categoría seleccionada no es válida.',
            'price.numeric'        => 'El precio debe ser un número.',
            'price.min'            => 'El precio no puede ser negativo.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('repository.index')
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        try {
            $file = $request->file('file');

            $fileName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                . '-' . time()
                . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs('documents', $fileName, 'public');

            $previewPath = null;
            if ($file->getClientOriginalExtension() !== 'pdf') {
                $previewFileName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . time() . '.pdf';
                $previewPath = 'documents/' . $previewFileName;

                Storage::disk('public')->put($previewPath, Pdf::loadView('repository.preview')->output());
            }

            Document::create([
                'user_id'      => auth()->id(),
                'category_id'  => $validated['category_id'] ?? null,
                'title'        => $validated['title'],
                'description'  => $validated['description'] ?? null,
                'file_path'    => $path,
                'preview_path' => $previewPath,
                'price'        => $validated['price'] ?? null,
                'is_free'      => empty($validated['price']),
            ]);

            return redirect()->route('repository.index')
                ->with('success', 'Documento subido correctamente.');

        } catch (\Exception $e) {
            return redirect()->route('repository.index')
                ->with('error', $e->getMessage())
                ->withInput();
        }
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
        ], [
            'title.required'       => 'El título es obligatorio.',
            'title.string'         => 'El título debe ser un texto válido.',
            'title.max'            => 'El título no puede superar los 255 caracteres.',
            'description.string'   => 'La descripción debe ser un texto válido.',
            'category_id.exists'   => 'La categoría seleccionada no es válida.',
            'price.numeric'        => 'El precio debe ser un número.',
            'price.min'            => 'El precio no puede ser negativo.',
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
        $paymentSetting = PaymentSetting::first();

        return view('repository.gallery', compact('documents', 'categories', 'paymentSetting'));
    }



    /**
     * DESCARGA SEGURA
     */
    public function download(Document $document)
    {
        $user = auth()->user();

        if ($document->is_free || ($user && $user->hasRole('Administrador'))) {
            return $this->serveFile($document);
        }

        $purchased = $user
            ? $user->purchases()->where('document_id', $document->id)->exists()
            : false;

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

    public function toggleActive(Document $document)
    {
        if ($document->is_active) {
            $document->is_active = false;
            $document->save();
            $document->delete();
        } else {
            $document->restore();
            $document->is_active = true;
            $document->save();
        }

        return back()->with('success', $document->is_active
            ? 'Documento activado.'
            : 'Documento eliminado.');
    }



}
