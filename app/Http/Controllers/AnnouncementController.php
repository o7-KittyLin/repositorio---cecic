<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::orderBy('start_time', 'desc')->paginate(10);
        return view('announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('announcements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'link' => 'required|url',
            'status' => 'required|in:active,inactive,cancelled'
        ], [
            'title.required' => 'El título es obligatorio.',
            'title.string' => 'El título debe ser un texto válido.',
            'title.max' => 'El título no puede superar los 255 caracteres.',
            'description.string' => 'La descripción debe ser un texto válido.',
            'start_time.required' => 'La fecha de inicio es obligatoria.',
            'start_time.date' => 'La fecha de inicio no es válida.',
            'end_time.required' => 'La fecha de finalización es obligatoria.',
            'end_time.date' => 'La fecha de finalización no es válida.',
            'end_time.after' => 'La fecha de finalización debe ser posterior a la fecha de inicio.',
            'link.url' => 'El enlace debe ser una URL válida.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado seleccionado no es válido.'
        ]);

        Announcement::create($request->all());

        // Limpiar cache de anuncios activos
        Cache::forget('active_announcements');

        return redirect()->route('announcements.index')
            ->with('success', 'Anuncio creado correctamente.');
    }

    public function edit(Announcement $announcement)
    {
        return view('announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'link' => 'required|url',
            'status' => 'required|in:active,inactive,cancelled'
        ], [
            'title.required' => 'El título es obligatorio.',
            'title.string' => 'El título debe ser un texto válido.',
            'title.max' => 'El título no puede superar los 255 caracteres.',
            'description.string' => 'La descripción debe ser un texto válido.',
            'start_time.required' => 'La fecha de inicio es obligatoria.',
            'start_time.date' => 'La fecha de inicio no es válida.',
            'end_time.required' => 'La fecha de finalización es obligatoria.',
            'end_time.date' => 'La fecha de finalización no es válida.',
            'end_time.after' => 'La fecha de finalización debe ser posterior a la fecha de inicio.',
            'link.url' => 'El enlace debe ser una URL válida.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado seleccionado no es válido.'
        ]);


        $announcement->update($request->all());

        // Limpiar cache
        Cache::forget('active_announcements');

        return redirect()->route('announcements.index')
            ->with('success', 'Anuncio actualizado correctamente.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        Cache::forget('active_announcements');

        return back()->with('success', 'Anuncio eliminado.');
    }

    // Obtener anuncios activos para mostrar en la página principal
    public function getActiveAnnouncements()
    {
        return Cache::remember('active_announcements', 300, function () { // 5 minutos
            return Announcement::active()->get();
        });
    }
}
