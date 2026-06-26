<?php

namespace App\Livewire;

use App\Models\Workspace;
use Livewire\Component;

class BoardManager extends Component
{
    // Propiedad para identificar en qué espacio de trabajo estamos parado
    public $workspaceId;

    // Propiedades para el formulario de creación de un tablero
    public $title;

    public $color = '#4f46e5'; // Color por defecto (un violeta/índigo de Tailwind)

    // Reglas de validación para el formulario
    protected $rules = [
        'title' => 'required|min:3|max:100',
        'color' => 'required|string',
    ];

    // El método mount corre una sola vez cuando el componente se carga en pantalla
    // Recibe el ID del espacio de trabajo desde la URL o el componente padre
    public function mount($workspaceId)
    {
        $this->workspaceId = $workspaceId;
    }

    // Función para crear un nuevo tablero dentro de este espacio (HU 4)
    public function createBoard()
    {
        // Ejecuta las validaciones
        $this->validate();

        // Buscamos el espacio de trabajo actual
        $workspace = Workspace::findOrFail($this->workspaceId);

        // Creamos el tablero asociado a ese espacio
        $workspace->boards()->create([
            'title' => $this->title,
            'color' => $this->color,
        ]);

        // Limpiamos el campo del título para el próximo tablero
        $this->reset('title');

        // Enviamos una alerta de éxito a la pantalla
        session()->flash('message', '¡Tablero creado correctamente!');
    }

    // El render se ejecuta constantemente para actualizar la vista en tiempo real (HU 8)
    public function render()
    {
        // Traemos el espacio de trabajo con todos sus tableros asociados
        $workspace = Workspace::with('boards')->findOrFail($this->workspaceId);

        return view('livewire.board-manager', [
            'workspace' => $workspace,
            'boards' => $workspace->boards,
        ]);
    }
}
