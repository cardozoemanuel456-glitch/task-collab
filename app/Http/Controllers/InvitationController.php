<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    /**
     * Aceptar invitación mediante el enlace del correo (Token)
     */
    public function acceptByToken($token)
    {
        // Buscar invitación válida
        $invitation = Invitation::where('token_hash', hash('sha256', $token))
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->first();

        if (! $invitation) {
            return redirect()->route('paginas.index')
                ->with('error', 'La invitación no es válida, ya fue usada o ha expirado.');
        }

        return $this->processAcceptance($invitation, false);
    }

    /**
     * Aceptar invitación mediante el código corto (API para el frontend)
     */
    public function acceptByCode(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $invitation = Invitation::findByCode($request->code);

        if (! $invitation) {
            return redirect()->route('dashboard')
                ->with('error', 'Código inválido o expirado. Verificá el código del correo e intentá nuevamente.');
        }

        return $this->processAcceptance($invitation, false);
    }

    /**
     * Lógica común para procesar la aceptación (unir usuario a la página)
     */
    private function processAcceptance($invitation, $isJson = false)
    {
        $user = auth()->user();

        // 1. Seguridad: Verificar si el usuario está logueado
        if (! $user) {
            // Guardamos el ID de la invitación en la sesión
            session(['invitacion_id' => $invitation->id]);

            return redirect()->route('login')->with('status', 'Debes iniciar sesión para aceptar la invitación.');
        }

        // 2. Verificar si ya es miembro (CORRECCIÓN: Usar paginasCompartidas)
        // Usamos 'paginas.id' porque paginasCompartidas hace un JOIN con la tabla 'paginas'
        if ($user->paginasCompartidas()->where('paginas.id', $invitation->pagina_id)->exists()) {
            $msg = 'Ya eres miembro de esta página.';

            return $isJson
                ? response()->json(['success' => true, 'message' => $msg])
                : redirect()->route('paginas.show', $invitation->pagina_id)->with('info', $msg);
        }

        // 3. Unir usuario a la página (CORRECCIÓN: Usar paginasCompartidas)
        // Asegúrate de pasar el rol si tu tabla pivote lo requiere (ej. 'member', 'editor', etc.)
        // Si no defines un rol por defecto, Laravel usará NULL (si la columna lo permite)
        $user->paginasCompartidas()->attach($invitation->pagina_id, ['role' => 'member']);

        // 4. Actualizar estado de la invitación
        $invitation->update([
            'status' => 'accepted',
            'accepted_at' => now(),
            'accepted_by_user_id' => $user->id,
        ]);

        // 5. Respuesta final
        $msg = '¡Te has unido a la página con éxito!';

        return $isJson
            ? response()->json(['success' => true, 'message' => $msg])
            : redirect()->route('paginas.show', $invitation->pagina_id)->with('success', $msg);
    }
}
