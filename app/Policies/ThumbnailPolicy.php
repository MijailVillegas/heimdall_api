<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Thumbnail;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThumbnailPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the thumbnail can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('list thumbnails');
    }

    /**
     * Determine whether the thumbnail can view the model.
     */
    public function view(User $user, Thumbnail $model): bool
    {
        return $user->hasPermissionTo('view thumbnails');
    }

    /**
     * Determine whether the thumbnail can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create thumbnails');
    }

    /**
     * Determine whether the thumbnail can update the model.
     */
    public function update(User $user, Thumbnail $model): bool
    {
        return $user->hasPermissionTo('update thumbnails');
    }

    /**
     * Determine whether the thumbnail can delete the model.
     */
    public function delete(User $user, Thumbnail $model): bool
    {
        return $user->hasPermissionTo('delete thumbnails');
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     */
    public function deleteAny(User $user): bool
    {
        return $user->hasPermissionTo('delete thumbnails');
    }

    /**
     * Determine whether the thumbnail can restore the model.
     */
    public function restore(User $user, Thumbnail $model): bool
    {
        return false;
    }

    /**
     * Determine whether the thumbnail can permanently delete the model.
     */
    public function forceDelete(User $user, Thumbnail $model): bool
    {
        return false;
    }
}
