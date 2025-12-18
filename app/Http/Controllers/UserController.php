<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\DoubleHash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    private function ensureAdmin(): void
    {
        if (!auth()->user() || !auth()->user()->hasRole('Administrador')) {
            abort(403);
        }
    }

    public function index()
    {
        $this->ensureAdmin();

        $users = User::with('roles')->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $this->ensureAdmin();
        $roles = Role::whereIn('name', ['Administrador', 'Empleado'])->get();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|exists:roles,name'
        ]);

        $allowed = ['Administrador', 'Empleado'];
        if (!in_array($request->role, $allowed)) {
            return back()->withErrors(['role' => 'Rol no permitido.'])->withInput();
        }

        $doubleHash = new DoubleHash();
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $doubleHash->make($request->password),
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $user)
    {
        $isAdmin = auth()->user()->hasRole('Administrador');
        if (!$isAdmin && auth()->id() !== $user->id) {
            abort(403);
        }

        $roles = Role::whereIn('name', ['Administrador', 'Empleado'])->get();
        return view('users.edit', compact('user', 'roles', 'isAdmin'));
    }

    public function update(Request $request, User $user)
    {
        $isAdmin = auth()->user()->hasRole('Administrador');
        if (!$isAdmin && auth()->id() !== $user->id) {
            abort(403);
        }

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'role'     => 'nullable|exists:roles,name'
        ], [
            'name.required'     => 'El nombre es obligatorio.',
            'name.string'       => 'El nombre debe ser un texto válido.',
            'name.max'          => 'El nombre no puede superar los 255 caracteres.',
            'email.required'    => 'El correo electrónico es obligatorio.',
            'email.email'       => 'Debe ser un correo electrónico válido.',
            'email.unique'      => 'El correo electrónico ya está en uso.',
            'password.min'      => 'La contraseña debe tener al menos 6 caracteres.',
            'role.exists'       => 'El rol seleccionado no es válido.',
        ]);

        $doubleHash = new DoubleHash();

        $user->name = $request->name;
        $user->email = $request->email;

        $passwordChanged = false;
        if ($request->filled('password')) {
            $user->password = $doubleHash->make($request->password);
            $passwordChanged = true;
        }

        $user->save();

        if ($isAdmin && $request->filled('role')) {
            $allowed = ['Administrador', 'Empleado'];
            if (!in_array($request->role, $allowed)) {
                return back()->withErrors(['role' => 'Rol no permitido.'])->withInput();
            }
            $user->syncRoles([$request->role]);
        }

        if ($passwordChanged) {
            Auth::setUser($user);
            $request->session()->regenerate();
        }

        return redirect()->route('users.edit', $user->id)->with('success', 'Datos actualizados correctamente.');
    }

    public function destroy(User $user)
    {
        $this->ensureAdmin();
        $user->delete();
        return back()->with('success', 'Usuario eliminado.');
    }
}
