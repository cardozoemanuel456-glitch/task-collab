<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskBoard extends Component
{
    // Propiedades del Tablero
    public $tableros = [];
    public $invitationCode = '';
    public $showModal = false;
    public $showBoardModal = false;

    // Propiedades de los Formularios (wire:model)
    public $newTaskTitle = '';
    public $newTaskPriority = 'Baja';
    public $newBoardName = '';

    // Reglas de validación
    protected $rules = [
        'newBoardName' => 'required|min:3',
    ];

    public function mount()
    {
        // Inicialización de datos de ejemplo
        $this->tableros = ['Proyecto Laravel'];
        $this->invitationCode = 'TK-' . strtoupper(substr(md5(Auth::id() . time()), 0, 6));
    }

    public function render()
    {
        // Trae únicamente las tareas que pertenecen al usuario logueado
        $tasks = Task::where('user_id', Auth::id())->get();
        return view('livewire.task-board', [
            'tasks' => $tasks
        ]);
    }

    // --- MÉTODOS PARA MODAL DE TAREAS ---
    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['newTaskTitle', 'newTaskPriority']);
    }

    public function addTask()
    {
        $this->validate([
            'newTaskTitle' => 'required|min:3',
            'newTaskPriority' => 'required'
        ]);

        // Guardado seguro vinculando el user_id del usuario autenticado
        Task::create([
            'title' => $this->newTaskTitle,
            'priority' => $this->newTaskPriority,
            'status' => 'por_hacer',
            'user_id' => Auth::id(),
        ]);

        $this->closeModal();
    }

    // --- MÉTODOS DE CONTROL DE TAREAS ---
    public function moveTask($taskId, $status)
    {
        $task = Task::where('user_id', Auth::id())->find($taskId);
        if ($task) {
            $task->update(['status' => $status]);
        }
    }

    public function deleteTask($taskId)
    {
        $task = Task::where('user_id', Auth::id())->find($taskId);
        if ($task) {
            $task->delete();
        }
    }

    // --- MÉTODO NUEVO: MODIFICAR/RENOMBRAR TAREA ---
    public function updateTaskTitle($taskId, $newTitle)
    {
        // Validación rápida en línea para no romper la estructura anterior
        if (empty(trim($newTitle)) || strlen($newTitle) < 3) {
            return;
        }

        $task = Task::where('user_id', Auth::id())->find($taskId);
        if ($task) {
            $task->update(['title' => $newTitle]);
        }
    }

    // --- MÉTODOS PARA MODAL DE TABLEROS ---
    public function openCreateBoardModal()
    {
        $this->showBoardModal = true;
    }

    public function closeBoardModal()
    {
        $this->showBoardModal = false;
        $this->reset('newBoardName');
    }

    public function addBoard()
    {
        $this->validate();

        $this->tableros[] = $this->newBoardName;
        $this->closeBoardModal();
    }

    public function deleteBoard($index)
    {
        if (isset($this->tableros[$index])) {
            unset($this->tableros[$index]);
            $this->tableros = array_values($this->tableros); // Reindexar el array
        }
    }
}