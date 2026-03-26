<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'title',
        'description',
        'type',
        'start_time',
        'end_time',
        'link',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scope para anuncios activos
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where('start_time', '<=', now())
                    ->where('end_time', '>=', now());
    }

    public function scopeVisible($query)
    {
        return $query->where('status', 'active')
                     ->where(function ($q) {
                         $q->where('type', 'multimedia')
                           ->orWhere(function ($qq) {
                               $qq->where('type', 'reunion')
                                  ->where('end_time', '>=', now());
                           });
                     });
    }

    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    // Scope para anuncios programados
    public function scopeScheduled($query)
    {
        return $query->where('status', 'active')
                    ->where('start_time', '>', now());
    }

    // Scope para anuncios expirados
    public function scopeExpired($query)
    {
        return $query->where('end_time', '<', now());
    }

    // Método para verificar si el anuncio está activo
    public function isActive()
    {
        return $this->status === 'active' && 
               $this->start_time <= now() && 
               $this->end_time >= now();
    }

    // Método para verificar si está programado
    public function isScheduled()
    {
        return $this->status === 'active' && 
               $this->start_time > now();
    }

    // Método para verificar si está expirado
    public function isExpired()
    {
        return $this->end_time < now();
    }

    // Método para obtener el estado actual
    public function getCurrentStatusAttribute()
    {
        if ($this->status === 'cancelled') {
            return 'cancelado';
        }

        if ($this->status === 'inactive') {
            return 'inactivo';
        }

        if ($this->isExpired()) {
            return 'expirado';
        }

        if ($this->isScheduled()) {
            return 'programado';
        }

        if ($this->isActive()) {
            return 'en_curso';
        }

        return 'desconocido';
    }
}
