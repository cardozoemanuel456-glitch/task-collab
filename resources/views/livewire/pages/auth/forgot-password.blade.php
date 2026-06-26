<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component 
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink($this->only('email'));

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div class="w-full min-h-screen flex flex-col justify-center items-center bg-[#0b0f19] text-white p-6">

    <div class="w-full max-w-md bg-[#131926] p-8 rounded-2xl border border-gray-800 shadow-2xl">

        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold tracking-tight text-white">¿Olvidaste tu contraseña?</h2>
            <p class="text-sm text-gray-400 mt-2 px-2">
                No hay problema. Dinós tu dirección de correo electrónico y te enviaremos un enlace para restablecerla.
            </p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit="sendPasswordResetLink" class="space-y-5">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-300">Correo electrónico</label>
                <input wire:model="email" id="email" type="email" name="email" required autofocus
                    class="block mt-1 w-full rounded-lg bg-[#1c2333] border-gray-700 text-white focus:border-blue-500 focus:ring-blue-500 shadow-sm placeholder-gray-500"
                    placeholder="tu@correo.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="flex flex-col gap-3 mt-6">
                <button type="submit"
                    class="w-full px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm shadow-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Enviar enlace de restablecimiento
                </button>

                <a class="text-center text-sm text-gray-400 hover:text-white underline rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 pt-2"
                    href="{{ route('login') }}" wire:navigate>
                    Volver al inicio de sesión
                </a>
            </div>
        </form>
    </div>
</div>
