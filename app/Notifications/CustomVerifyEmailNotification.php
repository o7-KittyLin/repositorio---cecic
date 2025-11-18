<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;

class CustomVerifyEmailNotification extends VerifyEmailBase
{
    use Queueable;

    public function toMail($notifiable)
    {
        // URL de verificación que Laravel genera
        $verifyUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verifica tu correo en CECIC')
            ->view('emails.verify-email', [
                'user'      => $notifiable,
                'verifyUrl' => $verifyUrl,
            ]);
    }
}

