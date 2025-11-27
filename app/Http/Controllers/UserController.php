<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\DoubleHash;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|exists:roles,name'
        ], [
            'name.required'     => 'El nombre es obligatorio.',
            'name.string'       => 'El nombre debe ser un texto válido.',
            'name.max'          => 'El nombre no puede superar los 255 caracteres.',
            'email.required'    => 'El correo electrónico es obligatorio.',
            'email.email'       => 'Debe ser un correo electrónico válido.',
            'email.unique'      => 'El correo electrónico ya está en uso.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min'      => 'La contraseña debe tener al menos 6 caracteres.',
            'role.required'     => 'El rol es obligatorio.',
            'role.exists'       => 'El rol seleccionado no es válido.'
        ]);

        $doubleHash = new DoubleHash();
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $doubleHash->make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'nullable|min:6',
            'role'     => 'required|exists:roles,name'
        ], [
            'name.required'     => 'El nombre es obligatorio.',
            'name.string'       => 'El nombre debe ser un texto válido.',
            'name.max'          => 'El nombre no puede superar los 255 caracteres.',
            'email.required'    => 'El correo electrónico es obligatorio.',
            'email.email'       => 'Debe ser un correo electrónico válido.',
            'email.unique'      => 'El correo electrónico ya está en uso.',
            'password.min'      => 'La contraseña debe tener al menos 6 caracteres.',
            'role.required'     => 'El rol es obligatorio.',
            'role.exists'       => 'El rol seleccionado no es válido.'
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        $doubleHash = new DoubleHash();

        if ($request->filled('password')) {
            $user->update([
                'password' => $doubleHash->make($request->password)
            ]);
        }

        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'Usuario eliminado.');
    }
}
