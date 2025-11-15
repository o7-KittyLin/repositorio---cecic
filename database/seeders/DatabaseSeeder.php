<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'Administrador']);
        $userRole  = Role::firstOrCreate(['name' => 'Usuario']);

        // Crear usuario administrador
        $admin = User::firstOrCreate(
            ['email' => 'admin@cecic.com'],
            ['name' => 'Administrador CECIC', 'password' => bcrypt('admin123')]
        );

         $user = User::firstOrCreate(
            ['email' => 'user@cecic.com'],
            ['name' => 'Usuario CECIC', 'password' => bcrypt('usuario123')]
        );

        $admin->assignRole($adminRole);
        $user->assignRole($userRole);
    }
}
