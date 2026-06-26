<?php

namespace App\Policies;

use App\Models\Pagina;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        return $user->can('view', $task->pagina);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Pagina $pagina): bool
    {
        return $user->can('view', $pagina);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        return $user->can('view', $task->pagina);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->can('view', $task->pagina);
    }
}
