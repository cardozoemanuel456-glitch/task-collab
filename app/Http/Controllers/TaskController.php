<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validamos TODOS los datos que vienen del Modal
        $data = $request->validate([
            'pagina_id'   => 'required|exists:paginas,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority'    => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date',
        ]);

        // 2. Asignamos los datos automáticos
        $data['user_id'] = auth()->id(); // El creador de la tarea
        $data['status'] = 'todo';        // Estado inicial

        // 3. Guardamos en la Base de Datos
        Task::create($data);

        return back();
    }

    public function update(Request $request, Task $task)
    {
        // CASO A1: Drag & drop o actualización directa de status (sin título)
        if ($request->has('status') && !$request->has('title')) {
            $request->validate([
                'status' => 'required|string|in:todo,doing,done',
            ]);
            $task->update(['status' => $request->status]);
            
            return $request->wantsJson() 
                ? response()->json(['success' => true, 'task' => $task]) 
                : back();
        }

        // CASO A2: Viene del checkbox de la lista (solo queremos cambiar estado)
        if (!$request->has('title')) {
            $newStatus = $task->status === 'done' ? 'todo' : 'done';
            $task->update(['status' => $newStatus]);
            return back();
        }

        // CASO B: Viene del Modal completo (Editamos todo)
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'nullable|string|in:todo,doing,done',
            'priority'    => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date',
        ]);

        $task->update($data);

        return back();
    }

    public function destroy(Task $task)
    {
        $esCreadorDeTarea = $task->user_id === auth()->id();
        $esDuenoDePagina = $task->pagina->user_id === auth()->id();

        if (!$esCreadorDeTarea && !$esDuenoDePagina) {
            abort(403, 'No tenés permisos para eliminar esta tarea.');
        }

        $task->delete();

        return back();
    }
}