<?php

namespace App\Mail;

use App\Models\AccountDeletion;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountDeletionScheduled extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public AccountDeletion $deletion;

    public function __construct(User $user, AccountDeletion $deletion)
    {
        $this->user = $user;
        $this->deletion = $deletion;
    }

    public function build()
    {
        return $this->subject('Solicitud de eliminación de cuenta')
            ->view('emails.account-deletion-scheduled');
    }
}
