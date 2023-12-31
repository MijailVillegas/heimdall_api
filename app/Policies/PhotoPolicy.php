<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Photo;
use Illuminate\Auth\Access\HandlesAuthorization;

class PhotoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the photo can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('list photos');
    }

    /**
     * Determine whether the photo can view the model.
     */
    public function view(User $user, Photo $model): bool
    {
        return $user->hasPermissionTo('view photos');
    }

    /**
     * Determine whether the photo can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create photos');
    }

    /**
     * Determine whether the photo can update the model.
     */
    public function update(User $user, Photo $model): bool
    {
        return $user->hasPermissionTo('update photos');
    }

    /**
     * Determine whether the photo can delete the model.
     */
    public function delete(User $user, Photo $model): bool
    {
        return $user->hasPermissionTo('delete photos');
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     */
    public function deleteAny(User $user): bool
    {
        return $user->hasPermissionTo('delete photos');
    }

    /**
     * Determine whether the photo can restore the model.
     */
    public function restore(User $user, Photo $model): bool
    {
        return false;
    }

    /**
     * Determine whether the photo can permanently delete the model.
     */
    public function forceDelete(User $user, Photo $model): bool
    {
        return false;
    }
}
