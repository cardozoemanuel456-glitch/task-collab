<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Workspace;
use Illuminate\Support\Facades\Auth;

class WorkspaceManager extends Component
{
    // Variables para el formulario de creación
    public $name;
    public $description;

    // Variable para el formulario de unirse
    public $invite_code;

    // Reglas de validación
    protected $rules = [
        'name' => 'required|min:3|max:255',
        'description' => 'nullable|max:500',
    ];

    // Función A: Crear un nuevo Espacio de Trabajo (HU 4)
    public function createWorkspace()
    {
        $this->validate();

        // El 'invite_code' se genera solo en el modelo gracias al evento boot que programamos antes
        Auth::user()->workspaces()->create([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        // Limpiar los campos del formulario
        $this->reset(['name', 'description']);

        session()->flash('message', '¡Espacio de trabajo creado con éxito!');
    }

    // Función B: Unirse a un espacio con código de invitación (HU 5)
    public function joinWithCode()
    {
        $this->validate(['invite_code' => 'required']);

        // Buscamos si existe un espacio con ese código aleatorio
        $workspace = Workspace::where('invite_code', strtoupper($this->invite_code))->first();

        if (!$workspace) {
            session()->flash('error', 'El código de invitación no es válido.');
            return;
        }

        // Verificar si ya pertenece al espacio para no duplicarlo
        // Aquí interactuamos con los tableros del espacio o directamente lo registramos
        // Por ahora, le avisamos que el código es correcto
        session()->flash('message', '¡Te uniste correctamente a ' . $workspace->name . '!');
        $this->reset('invite_code');
    }

    // El render que alimenta a la vista (HU 8 - Listar proyectos)
    public function render()
    {
        // Trae solo los espacios que le pertenecen al usuario logueado
        $myWorkspaces = Auth::user()->workspaces;

        return view('livewire.workspace-manager', [
            'workspaces' => $myWorkspaces
        ]);
    }
}