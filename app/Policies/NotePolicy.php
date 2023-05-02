<?php

namespace App\Policies;

use App\Models\Note;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the note can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('list notes');
    }

    /**
     * Determine whether the note can view the model.
     */
    public function view(User $user, Note $model): bool
    {
        return $user->hasPermissionTo('view notes');
    }

    /**
     * Determine whether the note can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create notes');
    }

    /**
     * Determine whether the note can update the model.
     */
    public function update(User $user, Note $model): bool
    {
        return $user->hasPermissionTo('update notes');
    }

    /**
     * Determine whether the note can delete the model.
     */
    public function delete(User $user, Note $model): bool
    {
        return $user->hasPermissionTo('delete notes');
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     */
    public function deleteAny(User $user): bool
    {
        return $user->hasPermissionTo('delete notes');
    }

    /**
     * Determine whether the note can restore the model.
     */
    public function restore(User $user, Note $model): bool
    {
        return false;
    }

    /**
     * Determine whether the note can permanently delete the model.
     */
    public function forceDelete(User $user, Note $model): bool
    {
        return false;
    }
}
