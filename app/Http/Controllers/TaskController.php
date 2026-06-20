<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        // Validamos el título y que la página destino exista
        $request->validate([
            'title' => 'required|string|max:255',
            'pagina_id' => 'required|exists:paginas,id'
        ]);

        Task::create([
            'title' => $request->title,
            'user_id' => auth()->id(),
            'pagina_id' => $request->pagina_id, // El nuevo vínculo relacional
            'status' => 'todo'
        ]);

        return back();
    }

    public function update(Task $task)
    {
        // Mantenemos tu lógica dinámica de cambiar el estado
        $newStatus = $task->status === 'todo' ? 'done' : 'todo';
        $task->update(['status' => $newStatus]);

        return back();
    }

    public function destroy(Task $task)
    {
        // REGLA ACTUALIZADA: Podés borrarla si la creaste vos, o si sos el dueño de la página
        $esCreadorDeTarea = $task->user_id === auth()->id();
        $esDuenoDePagina = $task->pagina->user_id === auth()->id();

        if (!$esCreadorDeTarea && !$esDuenoDePagina) {
            abort(403, 'No tenés permisos para eliminar esta tarea.');
        }

        $task->delete();

        return back();
    }
}