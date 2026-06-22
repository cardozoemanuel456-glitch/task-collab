<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="w-full min-h-screen flex flex-col justify-center items-center bg-[#0b0f19] text-white p-6">
    
    <div class="w-full max-w-md bg-[#131926] p-8 rounded-2xl border border-gray-800 shadow-2xl">
        
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold tracking-tight text-white">Crea tu cuenta</h2>
            <p class="text-sm text-gray-400 mt-1">Súmate a TaskCollab para gestionar tus tareas</p>
        </div>

        <form wire:submit="register" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-300">Nombre completo</label>
                <input wire:model="name" id="name" type="text" name="name" required autofocus autocomplete="name"
                    class="block mt-1 w-full rounded-lg bg-[#1c2333] border-gray-700 text-white focus:border-blue-500 focus:ring-blue-500 shadow-sm" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-300">Correo electrónico</label>
                <input wire:model="email" id="email" type="email" name="email" required autocomplete="username"
                    class="block mt-1 w-full rounded-lg bg-[#1c2333] border-gray-700 text-white focus:border-blue-500 focus:ring-blue-500 shadow-sm" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-300">Contraseña</label>
                <input wire:model="password" id="password" type="password" name="password" required autocomplete="new-password"
                    class="block mt-1 w-full rounded-lg bg-[#1c2333] border-gray-700 text-white focus:border-blue-500 focus:ring-blue-500 shadow-sm" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-300">Confirmar contraseña</label>
                <input wire:model="password_confirmation" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="block mt-1 w-full rounded-lg bg-[#1c2333] border-gray-700 text-white focus:border-blue-500 focus:ring-blue-500 shadow-sm" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between mt-6 pt-2">
                <a class="text-sm text-gray-400 hover:text-white underline rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                   href="{{ route('login') }}" wire:navigate>
                    ¿Ya tienes cuenta? Logueate
                </a>

                <button type="submit" class="ms-4 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm shadow-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Registrarse
                </button>
            </div>
        </form>
    </div>
</div>
