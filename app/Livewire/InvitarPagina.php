<?php

namespace App\Livewire;

use App\Mail\PaginaInvitation;
use App\Models\Invitation;
use App\Models\Pagina;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class InvitarPagina extends Component
{
    public $paginaId;
    public $email = '';
    public $successMessage = '';
    public $inviteCode = '';
    public $errorMessage = '';
    public $isLoading = false;

    protected $rules = [
        'email' => 'required|email',
    ];

    public function send()
    {
        // 1. Validación estricta (RFC y DNS)
        $this->validate([
            'email' => 'required|email:rfc,dns',
        ]);

        $this->isLoading = true;
        $this->successMessage = '';
        $this->errorMessage = '';
        $this->inviteCode = '';

        try {
            // 2. Verificar que la página existe
            $pagina = Pagina::findOrFail($this->paginaId);
            $inviterId = Auth::id();
            $inviterName = Auth::user()->name;

            // 3. Verificar duplicados pendientes
            $existing = Invitation::where('pagina_id', $this->paginaId)
                ->where('email', $this->email)
                ->where('status', 'pending')
                ->first();

            if ($existing) {
                $this->inviteCode = $existing->code;
                $this->successMessage = "El usuario ya fue invitado previamente. Código: " . $existing->code;
                $this->isLoading = false;
                return;
            }

            // 4. Crear invitación
            $result = Invitation::createInvitation($this->paginaId, $inviterId, $this->email);
            $invitation = $result['invitation'];
            $plainToken = $result['token'];

            if (!$result['isNew']) {
                $this->successMessage = "Invitación ya existente.";
                $this->isLoading = false;
                return;
            }

            // 5. Generar URL
            $inviteUrl = route('paginas.invitar.accept', $plainToken);

            // 6. Enviar correo
            Mail::to($this->email)->send(new PaginaInvitation(
                $invitation->code,
                $pagina->nombre,
                $inviteUrl,
                $inviterName
            ));

            // 7. Éxito
            $this->inviteCode = $invitation->code;
            $this->successMessage = "¡Invitación enviada exitosamente! Código: " . $invitation->code;
            $this->email = '';

        } catch (\Exception $e) {
            \Log::error("Error en invitación: " . $e->getMessage());
            $this->errorMessage = "Hubo un error al procesar la invitación. Intente más tarde.";
        } finally {
            $this->isLoading = false;
        }
    }
}