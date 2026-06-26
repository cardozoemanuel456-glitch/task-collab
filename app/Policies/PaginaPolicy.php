<?php

namespace App\Policies;

use App\Models\Pagina;
use App\Models\User;

class PaginaPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Pagina $pagina): bool
    {
        return $user->id === $pagina->user_id || $pagina->miembros()->where('user_id', $user->id)->exists();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Pagina $pagina): bool
    {
        return $user->id === $pagina->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Pagina $pagina): bool
    {
        return $user->id === $pagina->user_id;
    }

    /**
     * Determine whether the user can invite members to the model.
     */
    public function invite(User $user, Pagina $pagina): bool
    {
        return $user->id === $pagina->user_id;
    }
}
