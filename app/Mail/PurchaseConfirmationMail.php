<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PurchaseConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public Document $document;
    public string $confirmUrl;

    public function __construct(User $user, Document $document, string $confirmUrl)
    {
        $this->user = $user;
        $this->document = $document;
        $this->confirmUrl = $confirmUrl;
    }

    public function build()
    {
        return $this->subject('Confirma tu compra en CECIC')
                    ->view('emails.purchase-confirmation');
    }
}
