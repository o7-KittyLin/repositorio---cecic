<?php

namespace App\Console\Commands;

use App\Mail\AccountDeletionCompleted;
use App\Models\AccountDeletion;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ProcessAccountDeletions extends Command
{
    protected $signature = 'account-deletions:process';

    protected $description = 'Procesa y completa las eliminaciones de cuenta programadas';

    public function handle(): int
    {
        $pending = AccountDeletion::whereNull('canceled_at')
            ->whereNull('completed_at')
            ->where('scheduled_for', '<=', now())
            ->get();

        foreach ($pending as $deletion) {
            $user = $deletion->user;
            if (! $user) {
                $deletion->completed_at = now();
                $deletion->save();
                continue;
            }

            $originalEmail = $user->email;

            $user->name = 'Usuario eliminado';
            $user->email = 'deleted+' . $user->id . '@example.invalid';
            $user->password = Hash::make(Str::random(40));
            $user->remember_token = null;
            $user->email_verified_at = null;
            $user->save();

            $deletion->completed_at = now();
            $deletion->save();

            Mail::to($originalEmail)->send(new AccountDeletionCompleted($user, $deletion));
        }

        $this->info('Eliminaciones procesadas: ' . $pending->count());

        return Command::SUCCESS;
    }
}
