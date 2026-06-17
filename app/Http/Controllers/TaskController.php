<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        // Validamos el título de la tarea
        $request->validate([
            'title' => 'required|string|max:255'
        ]);

        // Si el usuario está trabajando en el tablero de otro, usamos ese team_id. Si no, su propio ID.
        $teamId = session('current_team_id', auth()->id());

        Task::create([
            'title' => $request->title,
            'user_id' => auth()->id(),
            'team_id' => $teamId,
            'status' => 'todo'
        ]);

        return back();
    }

    public function update(Task $task)
    {
        // Cambia el estado de forma dinámica
        $newStatus = $task->status === 'todo' ? 'done' : 'todo';
        $task->update(['status' => $newStatus]);

        return back();
    }

    public function destroy(Task $task)
    {
        // REGLA COLABORATIVA: Podés borrar la tarea si la creaste VOS O si pertenece al tablero actual donde estás parado
        $currentTeamId = session('current_team_id', auth()->id());

        if ($task->user_id !== auth()->id() && $task->team_id !== $currentTeamId) {
            abort(403, 'No tenés permisos para eliminar esta tarjeta.');
        }

        $task->delete();

        return back();
    }
}