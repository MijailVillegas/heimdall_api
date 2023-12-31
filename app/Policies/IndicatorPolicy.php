<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Indicator;
use Illuminate\Auth\Access\HandlesAuthorization;

class IndicatorPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the indicator can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('list indicators');
    }

    /**
     * Determine whether the indicator can view the model.
     */
    public function view(User $user, Indicator $model): bool
    {
        return $user->hasPermissionTo('view indicators');
    }

    /**
     * Determine whether the indicator can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create indicators');
    }

    /**
     * Determine whether the indicator can update the model.
     */
    public function update(User $user, Indicator $model): bool
    {
        return $user->hasPermissionTo('update indicators');
    }

    /**
     * Determine whether the indicator can delete the model.
     */
    public function delete(User $user, Indicator $model): bool
    {
        return $user->hasPermissionTo('delete indicators');
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     */
    public function deleteAny(User $user): bool
    {
        return $user->hasPermissionTo('delete indicators');
    }

    /**
     * Determine whether the indicator can restore the model.
     */
    public function restore(User $user, Indicator $model): bool
    {
        return false;
    }

    /**
     * Determine whether the indicator can permanently delete the model.
     */
    public function forceDelete(User $user, Indicator $model): bool
    {
        return false;
    }
}
