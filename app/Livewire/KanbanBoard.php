<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Board;
use App\Models\Task;

class KanbanBoard extends Component
{
    // ID del tablero actual para saber qué tareas mostrar
    public $boardId;

    // Escuchamos eventos por si otro componente (como el creador de tareas) agrega algo
    protected $listeners = ['taskCreated' => '$refresh'];

    // El mount recibe el ID del tablero desde la ruta de navegación
    public function mount($boardId)
    {
        $this->boardId = $boardId;
    }

    // 🔥 LA MAGIA DE LIVEWIRE: Cambiar el estado de una tarea al moverla de columna
    public function updateTaskStatus($taskId, $newStatus)
    {
        // Validamos que el estado sea uno de los tres permitidos
        $allowedStatuses = ['Por Hacer', 'En Proceso', 'Terminado'];
        
        if (!in_array($newStatus, $allowedStatuses)) {
            return;
        }

        // Buscamos la tarea y le actualizamos el estado
        $task = Task::where('board_id', $this->boardId)->findOrFail($taskId);
        $task->update([
            'status' => $newStatus
        ]);

        // Opcional: Mandar una notificación flash discreta
        session()->flash('message', 'Tarea actualizada.');
    }

    // Renderiza el tablero y divide las tareas en 3 colecciones limpias para la vista (HU 9)
    public function render()
    {
        // Traemos el tablero con sus datos básicos
        $board = Board::findOrFail($this->boardId);

        // Traemos las tareas del tablero separadas por su columna correspondiente
        $todoTasks = Task::where('board_id', $this->boardId)->where('status', 'Por Hacer')->get();
        $inProgressTasks = Task::where('board_id', $this->boardId)->where('status', 'En Proceso')->get();
        $doneTasks = Task::where('board_id', $this->boardId)->where('status', 'Terminado')->get();

        return view('livewire.kanban-board', [
            'board' => $board,
            'todoTasks' => $todoTasks,
            'inProgressTasks' => $inProgressTasks,
            'doneTasks' => $doneTasks,
        ]);
    }
}