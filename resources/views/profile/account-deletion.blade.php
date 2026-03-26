@php
    $pendingDeletion = \App\Models\AccountDeletion::pendingFor(auth()->user());
@endphp

<div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Eliminar cuenta</h3>
            @if($pendingDeletion)
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                    Eliminación programada para <strong>{{ $pendingDeletion->scheduled_for->format('d/m/Y') }}</strong>. Puedes recuperar tu cuenta antes de esa fecha.
                </p>
            @else
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                    Tu cuenta se eliminará en 3 días hábiles tras la solicitud. Se enviará un correo de confirmación.
                </p>
            @endif
        </div>

        @if($pendingDeletion)
            <form method="POST" action="{{ route('account-deletion.recover') }}">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Recuperar cuenta
                </button>
            </form>
        @else
            <button type="button" x-data x-on:click="$dispatch('open-delete-account')" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Eliminar cuenta
            </button>
        @endif
    </div>

    @if(!$pendingDeletion)
    <div x-data="{ open: false }"
         x-on:open-delete-account.window="open = true"
         x-show="open"
         class="mt-4">
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-md p-4">
            <p class="text-sm text-red-700 dark:text-red-300 mb-2">
                Ingresa tu contraseña para programar la eliminación (5 días hábiles).
            </p>
            <form method="POST" action="{{ route('account-deletion.request') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contraseña actual</label>
                    <input name="password" type="password" required
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-red-500 focus:ring-red-500">
                    @error('password')
                        <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Confirmar eliminación
                    </button>
                    <button type="button" class="text-sm text-gray-600 hover:underline" x-on:click="open = false">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
