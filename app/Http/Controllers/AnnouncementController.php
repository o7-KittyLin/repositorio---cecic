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
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:reunion,multimedia',
            'start_date' => 'required_if:type,reunion|date|nullable',
            'start_hour' => 'required_if:type,reunion|regex:/^\d{1,2}:\d{2}$/|nullable',
            'start_period' => 'required_if:type,reunion|in:AM,PM|nullable',
            'end_hour' => 'required_if:type,reunion|regex:/^\d{1,2}:\d{2}$/|nullable',
            'end_period' => 'required_if:type,reunion|in:AM,PM|nullable',
            'link' => 'required|url',
            'status' => 'nullable|in:active,inactive,cancelled',
        ], [
            'title.required' => 'El título es obligatorio.',
            'title.string' => 'El título debe ser un texto válido.',
            'title.max' => 'El título no puede superar los 255 caracteres.',
            'description.string' => 'La descripción debe ser un texto válido.',
            'type.required' => 'Debes seleccionar el tipo de anuncio.',
            'type.in' => 'Tipo de anuncio no válido.',
            'start_date.required_if' => 'La fecha es obligatoria para reuniones.',
            'start_date.date' => 'La fecha de inicio no es válida.',
            'start_hour.required_if' => 'La hora de inicio es obligatoria para reuniones.',
            'start_hour.regex' => 'Formato de hora de inicio inválido.',
            'start_period.required_if' => 'El periodo (AM/PM) de inicio es obligatorio.',
            'start_period.in' => 'Periodo de inicio inválido.',
            'end_hour.required_if' => 'La hora de fin es obligatoria para reuniones.',
            'end_hour.regex' => 'Formato de hora de fin inválido.',
            'end_period.required_if' => 'El periodo (AM/PM) de fin es obligatorio.',
            'end_period.in' => 'Periodo de fin inválido.',
            'link.required' => 'El enlace es obligatorio.',
            'link.url' => 'El enlace debe ser una URL válida.',
            'status.in' => 'El estado seleccionado no es válido.',
        ]);

        $to24Hour = function ($hourStr, $period) {
            [$hour, $minute] = explode(':', $hourStr);
            $hour = (int) $hour;
            $minute = (int) $minute;
            if (strtoupper($period) === 'PM' && $hour !== 12) {
                $hour += 12;
            }
            if (strtoupper($period) === 'AM' && $hour === 12) {
                $hour = 0;
            }
            return [$hour, $minute];
        };

        $type = $request->type;
        // Por defecto multimedia: ventana larga
        $startTime = now();
        $endTime = now()->addYears(5);

        if ($type === 'reunion') {
            [$startHour, $startMinute] = $to24Hour($request->start_hour, $request->start_period);
            [$endHour, $endMinute] = $to24Hour($request->end_hour, $request->end_period);

            if (!(($startHour >= 8 && $startHour <= 12) || ($startHour >= 13 && $startHour <= 17))) {
                return back()->withErrors(['start_hour' => 'La hora de inicio debe ser entre 8–12 PM o 1–5 PM'])->withInput();
            }
            if (!(($endHour >= 8 && $endHour <= 12) || ($endHour >= 13 && $endHour <= 17))) {
                return back()->withErrors(['end_hour' => 'La hora de fin debe estar dentro de la franja permitida'])->withInput();
            }

            $startTotalMinutes = $startHour * 60 + $startMinute;
            $endTotalMinutes = $endHour * 60 + $endMinute;

            if ($startHour < 12 && $endTotalMinutes > 12 * 60) {
                return back()->withErrors(['end_hour' => 'La hora de fin no puede pasar de 12:00 PM'])->withInput();
            }
            if ($startHour >= 13 && $endTotalMinutes > 17 * 60) {
                return back()->withErrors(['end_hour' => 'La hora de fin no puede pasar de 5:00 PM'])->withInput();
            }
            if ($endTotalMinutes <= $startTotalMinutes) {
                return back()->withErrors(['end_hour' => 'La hora de fin debe ser posterior a la hora de inicio'])->withInput();
            }

            $startTime = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $request->start_date . ' ' . sprintf('%02d:%02d:00', $startHour, $startMinute)
            );

            $endTime = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $request->start_date . ' ' . sprintf('%02d:%02d:00', $endHour, $endMinute)
            );
        }

        Announcement::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $type,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'link' => $request->link,
            'status' => $request->status ?? 'active',
        ]);

        $this->clearAnnouncementCaches();

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
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:reunion,multimedia',
            'start_date' => 'required_if:type,reunion|date|nullable',
            'start_hour' => 'required_if:type,reunion|regex:/^\d{1,2}:\d{2}$/|nullable',
            'start_period' => 'required_if:type,reunion|in:AM,PM|nullable',
            'end_hour' => 'required_if:type,reunion|regex:/^\d{1,2}:\d{2}$/|nullable',
            'end_period' => 'required_if:type,reunion|in:AM,PM|nullable',
            'link' => 'required|url',
            'status' => 'nullable|in:active,inactive,cancelled',
        ], [
            'title.required' => 'El título es obligatorio.',
            'title.string' => 'El título debe ser un texto válido.',
            'title.max' => 'El título no puede superar los 255 caracteres.',
            'description.string' => 'La descripción debe ser un texto válido.',
            'type.required' => 'Debes seleccionar el tipo de anuncio.',
            'type.in' => 'Tipo de anuncio no válido.',
            'start_date.required_if' => 'La fecha es obligatoria para reuniones.',
            'start_date.date' => 'La fecha de inicio no es válida.',
            'start_hour.required_if' => 'La hora de inicio es obligatoria para reuniones.',
            'start_hour.regex' => 'Formato de hora de inicio inválido.',
            'start_period.required_if' => 'El periodo (AM/PM) de inicio es obligatorio.',
            'start_period.in' => 'Periodo de inicio inválido.',
            'end_hour.required_if' => 'La hora de fin es obligatoria para reuniones.',
            'end_hour.regex' => 'Formato de hora de fin inválido.',
            'end_period.required_if' => 'El periodo (AM/PM) de fin es obligatorio.',
            'end_period.in' => 'Periodo de fin inválido.',
            'link.required' => 'El enlace es obligatorio.',
            'link.url' => 'El enlace debe ser una URL válida.',
            'status.in' => 'El estado seleccionado no es válido.',
        ]);

        $to24Hour = function ($hourStr, $period) {
            [$hour, $minute] = explode(':', $hourStr);
            $hour = (int) $hour;
            $minute = (int) $minute;
            if (strtoupper($period) === 'PM' && $hour !== 12) {
                $hour += 12;
            }
            if (strtoupper($period) === 'AM' && $hour === 12) {
                $hour = 0;
            }
            return [$hour, $minute];
        };

        $type = $request->type;
        $startTime = $announcement->start_time;
        $endTime = $announcement->end_time;

        if ($type === 'reunion') {
            [$startHour, $startMinute] = $to24Hour($request->start_hour, $request->start_period);
            [$endHour, $endMinute] = $to24Hour($request->end_hour, $request->end_period);

            if (!(($startHour >= 8 && $startHour <= 12) || ($startHour >= 13 && $startHour <= 17))) {
                return back()->withErrors(['start_hour' => 'La hora de inicio debe ser entre 8–12 PM o 1–5 PM'])->withInput();
            }
            if (!(($endHour >= 8 && $endHour <= 12) || ($endHour >= 13 && $endHour <= 17))) {
                return back()->withErrors(['end_hour' => 'La hora de fin debe estar dentro de la franja permitida'])->withInput();
            }

            $startTotalMinutes = $startHour * 60 + $startMinute;
            $endTotalMinutes = $endHour * 60 + $endMinute;

            if ($startHour < 12 && $endTotalMinutes > 12 * 60) {
                return back()->withErrors(['end_hour' => 'La hora de fin no puede pasar de 12:00 PM'])->withInput();
            }
            if ($startHour >= 13 && $endTotalMinutes > 17 * 60) {
                return back()->withErrors(['end_hour' => 'La hora de fin no puede pasar de 5:00 PM'])->withInput();
            }
            if ($endTotalMinutes <= $startTotalMinutes) {
                return back()->withErrors(['end_hour' => 'La hora de fin debe ser posterior a la hora de inicio'])->withInput();
            }

            $startTime = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $request->start_date . ' ' . sprintf('%02d:%02d:00', $startHour, $startMinute)
            );

            $endTime = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $request->start_date . ' ' . sprintf('%02d:%02d:00', $endHour, $endMinute)
            );
        } else {
            // Multimedia: mantener ventana larga
            $startTime = $announcement->start_time ?? now();
            $endTime = $announcement->end_time ?? now()->addYears(5);
        }

        $announcement->update([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $type,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'link' => $request->link,
            'status' => $request->status ?? $announcement->status ?? 'active',
        ]);

        $this->clearAnnouncementCaches();

        return redirect()->route('announcements.index')
            ->with('success', 'Anuncio actualizado correctamente.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        $this->clearAnnouncementCaches();

        return back()->with('success', 'Anuncio eliminado.');
    }

    public function getActiveAnnouncements()
    {
        return Cache::remember('active_announcements', 300, function () {
            return Announcement::active()->get();
        });
    }

    private function clearAnnouncementCaches(): void
    {
        Cache::forget('active_announcements');
        Cache::forget('active_announcements_reunion');
        Cache::forget('active_announcements_multimedia');
    }
}
