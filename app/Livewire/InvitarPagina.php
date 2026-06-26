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
        $this->validate();

        $this->isLoading = true;
        $this->successMessage = '';
        $this->errorMessage = '';
        $this->inviteCode = '';

        try {
            $pagina = Pagina::findOrFail($this->paginaId);
            $inviterId = Auth::id();
            $inviterName = Auth::user()->name;

            // 1. Crear invitación
            $result = Invitation::createInvitation($this->paginaId, $inviterId, $this->email);
            $invitation = $result['invitation'];
            $plainToken = $result['token'];

            if (!$result['isNew']) {
                $this->inviteCode = $invitation->code;
                $this->successMessage = "El usuario ya fue invitado previamente. Código: " . $invitation->code;
                $this->isLoading = false;
                return;
            }

            // 2. Generar URL
            $inviteUrl = route('paginas.invitar.accept', $plainToken);

            // 3. Enviar correo
            Mail::to($this->email)->send(new PaginaInvitation(
                $invitation->code,
                $pagina->nombre, // Asegúrate que tu modelo Pagina tenga 'nombre'
                $inviteUrl,
                $inviterName
            ));

            // 4. Mostrar éxito
            $this->inviteCode = $invitation->code;
            $this->successMessage = "¡Invitación enviada! Código: " . $invitation->code;
            $this->email = '';

        } catch (\Exception $e) {

            \Log::error("Error detallado: " . $e->getMessage());
            $this->errorMessage = "Error al enviar: " . $e->getMessage(); // Mostrar error en pantalla
            
        } finally {
            $this->isLoading = false;
        }
    }

    public function render()
    {
        return view('livewire.invitar-pagina');
    }
}