{{-- resources/views/announcements/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-brown text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-pencil-square"></i> Editar Anuncio
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('announcements.update', $announcement->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Titulo del Anuncio*</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title', $announcement->title) }}">
                                @error('title')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Descripcion</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description', $announcement->description) }}</textarea>
                                @error('description')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tipo*</label>
                                    <select name="type" class="form-select">
                                        <option value="reunion" {{ old('type', $announcement->type)=='reunion' ? 'selected' : '' }}>Reunion</option>
                                        <option value="multimedia" {{ old('type', $announcement->type)=='multimedia' ? 'selected' : '' }}>Multimedia</option>
                                    </select>
                                    @error('type')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Fecha de Inicio*</label>
                                    <input type="text" id="start_date" name="start_date" class="form-control" placeholder="Selecciona fecha" value="{{ old('start_date', $startDate) }}">
                                    @error('start_date')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Hora de Inicio*</label>
                                    <div class="d-flex">
                                        <input type="text" id="start_hour" name="start_hour" class="form-control" placeholder="Ej: 9:30" value="{{ old('start_hour', $startHour) }}">
                                        <select id="start_period" name="start_period" class="form-select ms-2">
                                            <option value="AM" {{ old('start_period', $startPeriod)=='AM'?'selected':'' }}>AM</option>
                                            <option value="PM" {{ old('start_period', $startPeriod)=='PM'?'selected':'' }}>PM</option>
                                        </select>
                                    </div>
                                    <div id="start_time_error" class="text-danger small"></div>
                                    @error('start_hour')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                    @error('start_period')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Seleccione hora entre 8-12 AM o 1-5 PM</small>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Hora de Fin*</label>
                                    <div class="d-flex">
                                        <input type="text" id="end_hour" name="end_hour" class="form-control" placeholder="Ej: 11:45" value="{{ old('end_hour', $endHour) }}">
                                        <select id="end_period" name="end_period" class="form-select ms-2">
                                            <option value="AM" {{ old('end_period', $endPeriod)=='AM'?'selected':'' }}>AM</option>
                                            <option value="PM" {{ old('end_period', $endPeriod)=='PM'?'selected':'' }}>PM</option>
                                        </select>
                                    </div>
                                    <div id="end_time_error" class="text-danger small"></div>
                                    @error('end_hour')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                    @error('end_period')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Posterior a la hora de inicio</small>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Link del contenido*</label>
                                <input type="url" name="link" class="form-control" value="{{ old('link', $announcement->link) }}" placeholder="https://youtube.com/... o https://meet.google.com/...">
                                @error('link')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Estado</label>
                                <select name="status" class="form-select">
                                    <option value="active" {{ old('status', $announcement->status)=='active' ? 'selected' : '' }}>Activo</option>
                                    <option value="inactive" {{ old('status', $announcement->status)=='inactive' ? 'selected' : '' }}>Inactivo</option>
                                    <option value="cancelled" {{ old('status', $announcement->status)=='cancelled' ? 'selected' : '' }}>Cancelado</option>
                                </select>
                                @error('status')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-brown">
                                <i class="bi bi-check-circle"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDate = document.getElementById('start_date');
    const startHourInput = document.getElementById('start_hour');
    const startPeriodSelect = document.getElementById('start_period');
    const endHourInput = document.getElementById('end_hour');
    const endPeriodSelect = document.getElementById('end_period');
    const startError = document.getElementById('start_time_error');
    const endError = document.getElementById('end_time_error');
    const typeSelect = document.querySelector('select[name="type"]');
    let holidays = [];

    const currentYear = new Date().getFullYear();
    Promise.all([
        fetch(`https://date.nager.at/api/v3/PublicHolidays/${currentYear}/CO`).then(r => r.json()),
        fetch(`https://date.nager.at/api/v3/PublicHolidays/${currentYear + 1}/CO`).then(r => r.json())
    ]).then(([curr, next]) => {
        holidays = [...curr, ...next].map(h => h.date);
    }).catch(err => console.warn('No se pudieron cargar los feriados:', err));

    flatpickr(startDate, {
        dateFormat: "Y-m-d",
        minDate: "today",
        locale: "es",
        disable: [
            function(date) {
                const day = date.getDay();
                const dateStr = date.toISOString().split('T')[0];
                return day === 0 || day === 6 || holidays.includes(dateStr);
            }
        ]
    });

    function to24Hour(hourStr, period) {
        const parts = hourStr.split(':');
        if (parts.length !== 2) return null;
        let h = parseInt(parts[0], 10);
        const m = parseInt(parts[1], 10);
        if (period.toUpperCase() === 'PM' && h !== 12) h += 12;
        if (period.toUpperCase() === 'AM' && h === 12) h = 0;
        return { hour: h, minute: m };
    }

    function isValidFormat(hourStr) {
        return /^\d{1,2}:\d{2}$/.test((hourStr || '').trim());
    }

    function isValidRange(t) {
        return t && ((t.hour >= 8 && t.hour <= 12) || (t.hour >= 13 && t.hour <= 17));
    }

    function validateStart() {
        if (!startError) return true;
        startError.textContent = '';

        const val = (startHourInput.value || '').trim();
        if (!isValidFormat(val)) {
            startError.textContent = 'Formato invalido HH:MM';
            return false;
        }

        const t = to24Hour(val, startPeriodSelect.value);
        if (!isValidRange(t)) {
            startError.textContent = 'Hora de inicio fuera de rango (8-12 AM / 1-5 PM)';
            return false;
        }
        return true;
    }

    function validateEnd() {
        if (!endError) return true;
        endError.textContent = '';

        const startValid = validateStart();
        if (!startValid) return false;

        const val = (endHourInput.value || '').trim();
        if (!isValidFormat(val)) {
            endError.textContent = 'Formato invalido HH:MM';
            return false;
        }

        const startTime = to24Hour(startHourInput.value, startPeriodSelect.value);
        const endTime = to24Hour(endHourInput.value, endPeriodSelect.value);

        if (!isValidRange(endTime)) {
            endError.textContent = 'Hora de fin fuera de rango (8-12 AM / 1-5 PM)';
            return false;
        }

        const startMinutes = startTime.hour * 60 + startTime.minute;
        const endMinutes = endTime.hour * 60 + endTime.minute;

        if (endMinutes <= startMinutes) {
            endError.textContent = 'La hora de fin debe ser posterior a la hora de inicio';
            return false;
        }

        return true;
    }

    startHourInput.addEventListener('input', validateStart);
    startPeriodSelect.addEventListener('change', validateStart);
    endHourInput.addEventListener('input', validateEnd);
    endPeriodSelect.addEventListener('change', validateEnd);

    function toggleScheduleFields() {
        const isReunion = typeSelect && typeSelect.value === 'reunion';
        [startDate, startHourInput, startPeriodSelect, endHourInput, endPeriodSelect].forEach(el => {
            if (!el) return;
            el.disabled = !isReunion;
            el.closest('.col-md-4')?.classList.toggle('opacity-50', !isReunion);
        });
    }

    if (typeSelect) {
        typeSelect.addEventListener('change', toggleScheduleFields);
        toggleScheduleFields();
    }
});
</script>

<style>
    .flatpickr-calendar {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .flatpickr-time input:hover,
    .flatpickr-time input:focus {
        background: #f8f9fa;
    }
    input:disabled {
        background-color: #e9ecef;
        cursor: not-allowed;
    }
</style>
@endsection
