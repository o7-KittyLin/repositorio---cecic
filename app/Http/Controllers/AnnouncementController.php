<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Carbon\Carbon;
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
        // Validación básica de campos
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'start_hour' => 'required|regex:/^\d{1,2}:\d{2}$/',
            'start_period' => 'required|in:AM,PM',
            'end_hour' => 'required|regex:/^\d{1,2}:\d{2}$/',
            'end_period' => 'required|in:AM,PM',
            'link' => 'required|url',
            'status' => 'required|in:active,inactive,cancelled',
        ], [
            'title.required' => 'El título es obligatorio.',
            'title.string' => 'El título debe ser un texto válido.',
            'title.max' => 'El título no puede superar los 255 caracteres.',
            'description.string' => 'La descripción debe ser un texto válido.',
            'start_date.required' => 'La fecha de inicio es obligatoria.',
            'start_date.date' => 'La fecha de inicio no es válida.',
            'start_hour.required' => 'La hora de inicio es obligatoria.',
            'start_hour.regex' => 'Formato de hora de inicio inválido.',
            'start_period.required' => 'El periodo (AM/PM) de inicio es obligatorio.',
            'start_period.in' => 'Periodo de inicio inválido.',
            'end_hour.required' => 'La hora de fin es obligatoria.',
            'end_hour.regex' => 'Formato de hora de fin inválido.',
            'end_period.required' => 'El periodo (AM/PM) de fin es obligatorio.',
            'end_period.in' => 'Periodo de fin inválido.',
            'link.required' => 'El enlace es obligatorio.',
            'link.url' => 'El enlace debe ser una URL válida.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado seleccionado no es válido.',
        ]);

        // Función para convertir hora + periodo a 24h
        $to24Hour = function($hourStr, $period){
            [$hour, $minute] = explode(':', $hourStr);
            $hour = (int)$hour;
            $minute = (int)$minute;
            if(strtoupper($period) === 'PM' && $hour !== 12) $hour += 12;
            if(strtoupper($period) === 'AM' && $hour === 12) $hour = 0;
            return [$hour, $minute];
        };

        [$startHour, $startMinute] = $to24Hour($request->start_hour, $request->start_period);
        [$endHour, $endMinute] = $to24Hour($request->end_hour, $request->end_period);

        // Validación de franjas horarias
        if (!(($startHour >= 8 && $startHour <= 12) || ($startHour >= 13 && $startHour <= 17))) {
            return back()->withErrors(['start_hour' => 'La hora de inicio debe ser entre 8–12 AM o 1–5 PM'])->withInput();
        }
        if (!(($endHour >= 8 && $endHour <= 12) || ($endHour >= 13 && $endHour <= 17))) {
            return back()->withErrors(['end_hour' => 'La hora de fin debe estar dentro de la franja permitida'])->withInput();
        }

        $startTotalMinutes = $startHour * 60 + $startMinute;
        $endTotalMinutes = $endHour * 60 + $endMinute;

        // Validar que la hora de fin no se pase de 12:00 AM/PM o 17:00 PM
        if ($startHour < 12 && $endTotalMinutes > 12*60) {
            return back()->withErrors(['end_hour' => 'La hora de fin no puede pasar de 12:00 PM'])->withInput();
        }
        if ($startHour >= 13 && $endTotalMinutes > 17*60) {
            return back()->withErrors(['end_hour' => 'La hora de fin no puede pasar de 5:00 PM'])->withInput();
        }

        // Validar que la hora de fin sea posterior a la de inicio
        if ($endTotalMinutes <= $startTotalMinutes) {
            return back()->withErrors(['end_hour' => 'La hora de fin debe ser posterior a la hora de inicio'])->withInput();
        }

        // Crear objetos Carbon
        $startTime = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $request->start_date . ' ' . sprintf('%02d:%02d:00', $startHour, $startMinute)
        );

        $endTime = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $request->start_date . ' ' . sprintf('%02d:%02d:00', $endHour, $endMinute)
        );

        // Guardar
        Announcement::create([
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'link' => $request->link,
            'status' => $request->status,
        ]);

        Cache::forget('active_announcements');

        return redirect()->route('announcements.index')
            ->with('success', 'Anuncio creado correctamente.');
    }

    public function edit(Announcement $announcement)
    {
        // Separar fecha y horas para el formulario
        $startDate = $announcement->start_time->format('Y-m-d');
        $startHour = $announcement->start_time->format('g:i'); // hora en formato 12h
        $startPeriod = $announcement->start_time->format('A'); // AM/PM

        $endHour = $announcement->end_time->format('g:i');
        $endPeriod = $announcement->end_time->format('A');

        return view('announcements.edit', compact(
            'announcement',
            'startDate',
            'startHour',
            'startPeriod',
            'endHour',
            'endPeriod'
        ));
    }


    public function update(Request $request, Announcement $announcement)
    {
        // Validación básica de campos
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'start_hour' => 'required|regex:/^\d{1,2}:\d{2}$/',
            'start_period' => 'required|in:AM,PM',
            'end_hour' => 'required|regex:/^\d{1,2}:\d{2}$/',
            'end_period' => 'required|in:AM,PM',
            'link' => 'required|url',
            'status' => 'required|in:active,inactive,cancelled',
        ], [
            'title.required' => 'El título es obligatorio.',
            'title.string' => 'El título debe ser un texto válido.',
            'title.max' => 'El título no puede superar los 255 caracteres.',
            'description.string' => 'La descripción debe ser un texto válido.',
            'start_date.required' => 'La fecha de inicio es obligatoria.',
            'start_date.date' => 'La fecha de inicio no es válida.',
            'start_hour.required' => 'La hora de inicio es obligatoria.',
            'start_hour.regex' => 'Formato de hora de inicio inválido.',
            'start_period.required' => 'El periodo (AM/PM) de inicio es obligatorio.',
            'start_period.in' => 'Periodo de inicio inválido.',
            'end_hour.required' => 'La hora de fin es obligatoria.',
            'end_hour.regex' => 'Formato de hora de fin inválido.',
            'end_period.required' => 'El periodo (AM/PM) de fin es obligatorio.',
            'end_period.in' => 'Periodo de fin inválido.',
            'link.required' => 'El enlace es obligatorio.',
            'link.url' => 'El enlace debe ser una URL válida.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado seleccionado no es válido.',
        ]);

        // Función para convertir hora + periodo a 24h
        $to24Hour = function($hourStr, $period){
            [$hour, $minute] = explode(':', $hourStr);
            $hour = (int)$hour;
            $minute = (int)$minute;
            if(strtoupper($period) === 'PM' && $hour !== 12) $hour += 12;
            if(strtoupper($period) === 'AM' && $hour === 12) $hour = 0;
            return [$hour, $minute];
        };

        [$startHour, $startMinute] = $to24Hour($request->start_hour, $request->start_period);
        [$endHour, $endMinute] = $to24Hour($request->end_hour, $request->end_period);

        // Validación de franjas horarias
        if (!(($startHour >= 8 && $startHour <= 12) || ($startHour >= 13 && $startHour <= 17))) {
            return back()->withErrors(['start_hour' => 'La hora de inicio debe ser entre 8–12 AM o 1–5 PM'])->withInput();
        }
        if (!(($endHour >= 8 && $endHour <= 12) || ($endHour >= 13 && $endHour <= 17))) {
            return back()->withErrors(['end_hour' => 'La hora de fin debe estar dentro de la franja permitida'])->withInput();
        }

        $startTotalMinutes = $startHour * 60 + $startMinute;
        $endTotalMinutes = $endHour * 60 + $endMinute;

        // Validar que la hora de fin no se pase de 12:00 AM/PM o 17:00 PM
        if ($startHour < 12 && $endTotalMinutes > 12*60) {
            return back()->withErrors(['end_hour' => 'La hora de fin no puede pasar de 12:00 PM'])->withInput();
        }
        if ($startHour >= 13 && $endTotalMinutes > 17*60) {
            return back()->withErrors(['end_hour' => 'La hora de fin no puede pasar de 5:00 PM'])->withInput();
        }

        // Validar que la hora de fin sea posterior a la de inicio
        if ($endTotalMinutes <= $startTotalMinutes) {
            return back()->withErrors(['end_hour' => 'La hora de fin debe ser posterior a la hora de inicio'])->withInput();
        }

        // Crear objetos Carbon
        $startTime = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $request->start_date . ' ' . sprintf('%02d:%02d:00', $startHour, $startMinute)
        );

        $endTime = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $request->start_date . ' ' . sprintf('%02d:%02d:00', $endHour, $endMinute)
        );

        // Guardar
        $announcement->update([
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'link' => $request->link,
            'status' => $request->status,
        ]);

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
