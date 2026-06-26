<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use App\Notifications\TaskStatusUpdatedNotification;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    public function store(StoreTaskRequest $request)
    {
        $data = $request->validated();

        // 2. Asignamos los datos automáticos
        $data['user_id'] = auth()->id(); // El creador de la tarea
        $data['status'] = 'todo';        // Estado inicial

        // 3. Guardamos en la Base de Datos
        $task = Task::create($data);

        // Notificar si se asigna a otro usuario
        if ($task->assigned_to && $task->assigned_to !== auth()->id()) {
            $assignedUser = User::find($task->assigned_to);
            if ($assignedUser) {
                $assignedUser->notify(new TaskAssignedNotification($task, auth()->user()->name));
            }
        }

        return back();
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $oldAssignedTo = $task->assigned_to;
        $oldStatus = $task->status;

        // CASO A1: Drag & drop o actualización directa de status (sin título)
        if ($request->has('status') && ! $request->has('title')) {
            $data = $request->validated();
            $task->update(['status' => $data['status']]);

            $this->notifyStatusChange($task, $oldStatus, $data['status']);

            return $request->wantsJson()
                ? response()->json(['success' => true, 'task' => $task])
                : back();
        }

        // CASO A2: Viene del checkbox de la lista (solo queremos cambiar estado)
        if (! $request->has('title')) {
            $newStatus = $task->status === 'done' ? 'todo' : 'done';
            $task->update(['status' => $newStatus]);

            $this->notifyStatusChange($task, $oldStatus, $newStatus);

            return back();
        }

        // CASO B: Viene del Modal completo (Editamos todo)
        $data = $request->validated();

        $task->update($data);

        // Notificar si cambió la asignación y es a otro usuario
        if ($task->assigned_to !== $oldAssignedTo && $task->assigned_to && $task->assigned_to !== auth()->id()) {
            $assignedUser = User::find($task->assigned_to);
            if ($assignedUser) {
                $assignedUser->notify(new TaskAssignedNotification($task, auth()->user()->name));
            }
        }

        // Notificar cambio de estado
        if ($task->status !== $oldStatus) {
            $this->notifyStatusChange($task, $oldStatus, $task->status);
        }

        return back();
    }

    public function destroy(Task $task)
    {
        Gate::authorize('delete', $task);

        $task->delete();

        return back();
    }

    protected function notifyStatusChange(Task $task, string $oldStatus, string $newStatus)
    {
        if ($oldStatus === $newStatus) {
            return;
        }

        $authName = auth()->user()->name;

        // 1. Si hay un usuario asignado y no es quien hace el cambio, lo notificamos
        if ($task->assigned_to && $task->assigned_to !== auth()->id()) {
            $assignedUser = User::find($task->assigned_to);
            if ($assignedUser) {
                $assignedUser->notify(new TaskStatusUpdatedNotification($task, $authName, $newStatus));
            }
        }

        // 2. Si el creador de la tarea no es quien realiza el cambio, también lo notificamos
        if ($task->user_id !== auth()->id()) {
            $creator = User::find($task->user_id);
            if ($creator) {
                $creator->notify(new TaskStatusUpdatedNotification($task, $authName, $newStatus));
            }
        }
    }
}
