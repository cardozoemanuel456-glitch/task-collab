<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskAssignedNotification extends Notification
{
    use Queueable;

    protected $task;

    protected $assignerName;

    public function __construct(Task $task, string $assignerName)
    {
        $this->task = $task;
        $this->assignerName = $assignerName;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $pagina = $this->task->pagina;

        return [
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'pagina_id' => $pagina ? $pagina->id : null,
            'pagina_title' => $pagina ? $pagina->titulo : 'Sin título',
            'sender_name' => $this->assignerName,
            'message' => 'te asignó la tarea',
            'type' => 'task_assigned',
        ];
    }
}
