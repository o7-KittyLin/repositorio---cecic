<?php

namespace App\Http\Controllers;

use App\Mail\AccountDeletionScheduled;
use App\Models\AccountDeletion;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AccountDeletionController extends Controller
{
    public function requestDeletion(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ], [
            'password.current_password' => 'La contraseña no coincide.',
        ]);

        $user = $request->user();

        $existing = AccountDeletion::pendingFor($user);
        if ($existing) {
            return back()->with('info', 'Ya tienes una eliminación programada para el ' . $existing->scheduled_for->format('d/m/Y'));
        }

        $scheduledFor = $this->addBusinessDays(now(), 3);

        $deletion = AccountDeletion::create([
            'user_id' => $user->id,
            'scheduled_for' => $scheduledFor,
        ]);

        Mail::to($user->email)->send(new AccountDeletionScheduled($user, $deletion));

        return back()->with('success', 'Tu cuenta se eliminará el ' . $scheduledFor->format('d/m/Y') . '. Puedes recuperarla antes de esa fecha.');
    }

    public function recover(Request $request)
    {
        $user = $request->user();
        $pending = AccountDeletion::pendingFor($user);

        if (! $pending) {
            return back()->with('info', 'No hay una eliminación pendiente.');
        }

        $pending->canceled_at = now();
        $pending->save();

        return back()->with('success', 'La solicitud de eliminación fue cancelada. Tu cuenta sigue activa.');
    }

    private function addBusinessDays(Carbon $start, int $days): Carbon
    {
        $date = $start->copy();
        $added = 0;
        while ($added < $days) {
            $date->addDay();
            if (! $date->isWeekend()) {
                $added++;
            }
        }

        return $date;
    }
}
