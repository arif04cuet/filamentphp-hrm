<?php

namespace App\Policies;

use App\Models\Doptor;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DoptorPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Doptor  $doptor
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Doptor $doptor)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Doptor  $doptor
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Doptor $doptor)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Doptor  $doptor
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Doptor $doptor)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Doptor  $doptor
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function deleteAny(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Doptor  $doptor
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Doptor $doptor)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Doptor  $doptor
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Doptor $doptor)
    {
        return false;
    }
}