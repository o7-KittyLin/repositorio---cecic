<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class AccountDeletion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'scheduled_for',
        'canceled_at',
        'completed_at',
    ];

    protected $casts = [
        'scheduled_for' => 'datetime',
        'canceled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function pendingFor(User $user): ?self
    {
        return static::where('user_id', $user->id)
            ->whereNull('completed_at')
            ->whereNull('canceled_at')
            ->latest()
            ->first();
    }
}
