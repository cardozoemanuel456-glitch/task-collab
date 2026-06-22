<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
new #[Layout('layouts.guest')] class extends Component 
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
};
?>
<style>
    /* Versión compatible con el validador del editor */
    input:-webkit-autofill {
        -webkit-text-fill-color: #ffffff !important;
        box-shadow: 0 0 0px 1000px #131926 inset !important;
        -webkit-box-shadow: 0 0 0px 1000px #131926 inset !important;
    }
</style>

<div class="w-full min-h-screen flex flex-col md:flex-row bg-[#0b0f19] text-white">

    <div
        class="w-full md:w-1/2 bg-gradient-to-br from-blue-700 to-indigo-900 flex flex-col justify-center items-center p-10 text-center shadow-2xl">
        <span class="text-xs font-semibold tracking-widest uppercase opacity-60 mb-2">HARD-CODE DEV 2026</span>
        <h1 class="text-5xl font-extrabold tracking-tight mb-4 text-white">TaskCollab</h1>
        <p class="text-lg opacity-80 max-w-md text-gray-200">
            Unite al tablero y empezá a coordinar tus entregas y tareas con tu equipo en tiempo real.
        </p>
    </div>

    <div class="w-full md:w-1/2 flex flex-col justify-center items-center p-8 md:p-16 bg-[#0b0f19]">
        <div class="w-full max-w-md">

            <h2 class="text-2xl font-bold mb-1 text-left text-white">¡Hola de nuevo!</h2>
            <p class="text-sm text-gray-400 mb-6 text-left">Ingresá tus credenciales para acceder a la plataforma.</p>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form wire:submit="login" class="space-y-4">
                <div>
                    <x-input-label for="email" :value="__('Correo Electrónico')"
                        class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1" />
                    <x-text-input wire:model="form.email" id="email"
                        class="w-full bg-[#131926] border border-gray-800 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors shadow-none"
                        type="email" name="email" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('form.email')" class="mt-2 text-red-400 text-xs" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password" :value="__('Contraseña')"
                        class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1" />
                    <x-text-input wire:model="form.password" id="password"
                        class="w-full bg-[#131926] border border-gray-800 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors shadow-none"
                        type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('form.password')" class="mt-2 text-red-400 text-xs" />
                </div>

                <div class="flex items-center justify-between mt-4 text-sm text-gray-400">
                    <label for="remember" class="inline-flex items-center cursor-pointer">
                        <input wire:model="form.remember" id="remember" type="checkbox" name="remember"
                            class="rounded bg-[#131926] border-gray-800 text-blue-600 focus:ring-blue-500 focus:ring-offset-[#0b0f19]">
                        <span class="ms-2 text-xs text-gray-400">{{ __('Remember me') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-xs text-blue-400 hover:underline hover:text-blue-300 transition-colors"
                            href="{{ route('password.request') }}" wire:navigate>
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>

                <div class="pt-2">
                    <x-primary-button
                        class="w-full justify-center bg-blue-600 hover:bg-blue-500 text-white font-medium py-2.5 rounded-lg transition-colors shadow-lg shadow-blue-600/20 normal-case tracking-normal">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>
            </form>

            <div class="mt-6 text-center text-xs text-gray-400 border-t border-gray-900 pt-4">
                <p>¿No tienes una cuenta? <a href="{{ route('register') }}" wire:navigate
                        class="text-blue-400 hover:underline hover:text-blue-300 font-medium">Regístrate acá</a></p>
            </div>

        </div>
    </div>
</div>
