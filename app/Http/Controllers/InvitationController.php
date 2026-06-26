<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Pagina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller
{
    /**
     * Aceptar invitación mediante el enlace del correo (Token)
     */
    public function acceptByToken($token)
    {
        // Buscar invitación válida por token
        $invitation = Invitation::findByToken($token);

        if (!$invitation) {
            return redirect()->route('paginas.index')
                ->with('error', 'La invitación es inválida, ya fue usada o ha expirado.');
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

        if (!$invitation) {
            return response()->json([
                'success' => false,
                'message' => 'Código inválido o expirado.'
            ], 404);
        }

        return $this->processAcceptance($invitation, true);
    }

    /**
     * Lógica común para procesar la aceptación (unir usuario a la página)
     */
    private function processAcceptance($invitation, $isJson = false)
    {
        $user = Auth::user();

        // 1. Verificar si el usuario ya es miembro de esta página
        $paginasIds = $user->paginas()->get()->pluck('id')->toArray();

        if (in_array($invitation->pagina_id, $paginasIds)) {
            $message = 'Ya eres miembro de esta página.';

            if ($isJson) {
                return response()->json(['success' => true, 'message' => $message]);
            }

            return redirect()->route('paginas.show', $invitation->pagina_id)
                ->with('info', $message);
        }

        // 2. Unir usuario a la página (Relación Many-to-Many en tabla 'pagina_usuario')
        // Asegúrate de que tu modelo User tenga el método 'paginas()' definido
        $user->paginas()->attach($invitation->pagina_id);

        // 3. Marcar la invitación como aceptada en la base de datos
        $invitation->update([
            'status' => 'accepted',
            'accepted_at' => now(),
            'accepted_by_user_id' => $user->id,
        ]);

        // 4. Retornar respuesta
        if ($isJson) {
            return response()->json([
                'success' => true,
                'message' => '¡Te has unido a la página con éxito!',
                'redirect_url' => route('paginas.show', $invitation->pagina_id)
            ]);
        }

        return redirect()->route('paginas.show', $invitation->pagina_id)
            ->with('success', '¡Te has unido a la página exitosamente!');
    }
}