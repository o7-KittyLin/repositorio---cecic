<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {
        Validator::make(
            $input,
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => $this->passwordRules(),
                'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
            ],
            [
                'name.required' => 'El nombre es obligatorio.',
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'Debes ingresar un correo electrónico válido.',
                'email.unique' => 'Este correo ya está en uso.',
                'password.required' => 'La contraseña es obligatoria.',
            ],
            [
                'name' => 'nombre',
                'email' => 'correo electrónico',
                'password' => 'contraseña',
                'terms' => 'términos y condiciones',
            ]
        )->validate();

        // Crear usuario
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        // ASIGNAR ROL AUTOMÁTICO
        $user->assignRole('Usuario');

        return $user;
    }
}
