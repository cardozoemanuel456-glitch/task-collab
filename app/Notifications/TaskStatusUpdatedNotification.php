<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskStatusUpdatedNotification extends Notification
{
    use Queueable;

    protected $task;

    protected $updaterName;

    protected $newStatus;

    public function __construct(Task $task, string $updaterName, string $newStatus)
    {
        $this->task = $task;
        $this->updaterName = $updaterName;
        $this->newStatus = $newStatus;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $statusLabel = match ($this->newStatus) {
            'todo' => 'Por hacer',
            'doing' => 'En proceso',
            'done' => 'Terminado',
            default => $this->newStatus
        };

        $pagina = $this->task->pagina;

        return [
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'pagina_id' => $pagina ? $pagina->id : null,
            'pagina_title' => $pagina ? $pagina->titulo : 'Sin título',
            'sender_name' => $this->updaterName,
            'message' => 'cambió el estado de la tarea a "'.$statusLabel.'"',
            'type' => 'task_status_updated',
        ];
    }
}
