<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AttendanceList;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttendanceListPolicy
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
        if ($user->designation_id == 0)
            return true;
        else
            return false;
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AttendanceList  $AttendanceList
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, AttendanceList $AttendanceList)
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
     * @param  \App\Models\AttendanceList  $AttendanceList
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, AttendanceList $AttendanceList)
    {
        if ($user->designation_id == 0)
            return true;
        else
            return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AttendanceList  $AttendanceList
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, AttendanceList $AttendanceList)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AttendanceList  $AttendanceList
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
     * @param  \App\Models\AttendanceList  $AttendanceList
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, AttendanceList $AttendanceList)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AttendanceList  $AttendanceList
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, AttendanceList $AttendanceList)
    {
        return false;
    }
}
