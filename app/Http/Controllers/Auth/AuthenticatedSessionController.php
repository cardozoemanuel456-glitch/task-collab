<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Invitation; // Importar el modelo de invitación
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // LÓGICA NUEVA: Verificar si hay una invitación pendiente en la sesión
        $invitationId = $request->session()->get('invitacion_id');

        if ($invitationId) {
            $invitation = Invitation::find($invitationId);
            $user = Auth::user();

            if ($invitation && $invitation->status === 'pending') {
                // CORRECCIÓN: Usar paginasCompartidas y buscar en 'paginas.id'
                if (!$user->paginasCompartidas()->where('paginas.id', $invitation->pagina_id)->exists()) {

                    // CORRECCIÓN: Usar paginasCompartidas para attach
                    $user->paginasCompartidas()->attach($invitation->pagina_id, ['role' => 'member']);

                    // Actualizar estado de la invitación
                    $invitation->update([
                        'status' => 'accepted',
                        'accepted_at' => now(),
                        'accepted_by_user_id' => $user->id,
                    ]);

                    // Limpiar la sesión
                    $request->session()->forget('invitacion_id');

                    // Redirigir directamente a la página con mensaje de éxito
                    return redirect()->route('paginas.show', $invitation->pagina_id)
                        ->with('success', '¡Iniciaste sesión y te has unido a la página con éxito!');
                }
            }

            // Si la invitación ya no es válida (usada/expirada), limpiarla de la sesión
            $request->session()->forget('invitacion_id');
        }

        // Redirección por defecto (si no hay invitación o ya se procesó)
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}