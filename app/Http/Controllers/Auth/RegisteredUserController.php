<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Invitation; // <--- IMPORTANTE: Agregar esta línea
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // --- INICIO DEL CÓDIGO PARA PROCESAR LA INVITACIÓN ---
        
        // 1. Obtener el ID de la invitación de la sesión (guardado por InvitationController)
        $invitacionId = session('invitacion_id');

        // 2. Verificar si existe una invitación pendiente y el usuario coincide
        if ($invitacionId) {
            $invitation = Invitation::find($invitacionId);

            if ($invitation && $invitation->status === 'pending' && $invitation->email === $user->email) {
                
                // 3. Unir al usuario a la página compartida
                $user->paginasCompartidas()->attach($invitation->pagina_id, ['role' => 'member']);

                // 4. Actualizar el estado de la invitación a 'accepted'
                $invitation->update([
                    'status' => 'accepted',
                    'accepted_at' => now(),
                    'accepted_by_user_id' => $user->id,
                ]);

                // 5. Limpiar la sesión para evitar que se procese de nuevo
                session()->forget('invitacion_id');
            }
        }
        // --- FIN DEL CÓDIGO PARA PROCESAR LA INVITACIÓN ---

        return redirect(route('dashboard', absolute: false));
    }
}