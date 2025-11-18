<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordBase;

class CustomResetPasswordNotification extends ResetPasswordBase
{
    use Queueable;

    public function toMail($notifiable)
    {
        // URL de reseteo con token + email (como la estándar de Laravel)
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Restablece tu contraseña en CECIC')
            ->view('emails.password-reset', [
                'user'     => $notifiable,
                'resetUrl' => $resetUrl,
            ]);
    }
}