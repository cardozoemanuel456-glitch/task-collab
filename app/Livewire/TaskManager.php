<?php

namespace App\Livewire;

use App\Models\Board;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TaskManager extends Component
{
    // ID del tablero donde se va a meter la tarea
    public $boardId;

    // Propiedades mapeadas a los inputs del formulario
    public $title;

    public $description;

    public $start_date;

    public $end_date;

    public $assigned_to; // ID del compañero asignado

    // Reglas de validación estrictas
    protected $rules = [
        'title' => 'required|min:3|max:255',
        'description' => 'nullable|max:1000',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date', // Evita que la entrega sea antes del inicio
        'assigned_to' => 'nullable|exists:users,id',
    ];

    public function mount($boardId)
    {
        $this->boardId = $boardId;
    }

    // Función para procesar el formulario de una nueva tarea
    public function createTask()
    {
        $this->validate();

        // Creamos la tarea en la base de datos
        Task::create([
            'title' => $this->title,
            'description' => $this->description,
            'status' => 'Por Hacer', // Toda tarea nueva arranca en la primera columna
            'start_date' => $this->start_date ?: null,
            'end_date' => $this->end_date ?: null,
            'board_id' => $this->boardId,
            'user_id' => Auth::id(), // El usuario logueado es el creador
            'assigned_to' => $this->assigned_to ?: null,
        ]);

        // Limpiamos los campos del formulario
        $this->reset(['title', 'description', 'start_date', 'end_date', 'assigned_to']);

        // 🔥 COMUNICACIÓN EN TIEMPO REAL: Le avisa al KanbanBoard que hay una tarea nueva para que se refresque solo
        $this->dispatch('taskCreated');

        session()->flash('message', '¡Tarea asignada y creada correctamente!');
    }

    public function render()
    {
        // Buscamos los miembros del tablero para poder listarlos en el select de "Asignar a..."
        $board = Board::with('members')->findOrFail($this->boardId);
        $members = $board->members;

        // Por si todavía no tienen configurada la lógica de miembros,
        // traemos todos los usuarios como plan de respaldo para las pruebas iniciales
        $assignableUsers = $members->isNotEmpty() ? $members : User::all();

        return view('livewire.task-manager', [
            'users' => $assignableUsers,
        ]);
    }
}
