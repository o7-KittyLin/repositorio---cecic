<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:1000',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Debes ingresar un correo válido.',
            'message.required' => 'La descripción es obligatoria.',
            'message.max' => 'La descripción no puede superar los 1000 caracteres.',
        ]);

        $recipients = User::role(['Administrador', 'Empleado'])
            ->pluck('email')
            ->filter()
            ->unique();

        if ($recipients->isNotEmpty()) {
            Mail::to($recipients)->send(new ContactFormMail($data));
        }

        return back()->with('contact_success', 'Mensaje enviado. Nos pondremos en contacto pronto.');
    }
}
