<?php

namespace App\Policies;

use App\Models\OthersLeaveApplication;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OthersLeaveApplicationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny()
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OthersLeaveApplication  $OthersLeaveApplication
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view()
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create()
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OthersLeaveApplication  $OthersLeaveApplication
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update()
    {
        if (request()->route()->parameter('record') == null)
            return true;
        else {
            $id = request()->route()->parameter('record');
            $application = OthersLeaveApplication::where('id', $id)->first();
            if ($application) {
                if ($application->status == 'Pending')
                    return true;
                else
                    return redirect()->route('filament.resources.others-leave-applications.index');
            }
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OthersLeaveApplication  $OthersLeaveApplication
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete()
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OthersLeaveApplication  $OthersLeaveApplication
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
     * @param  \App\Models\OthersLeaveApplication  $OthersLeaveApplication
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore()
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OthersLeaveApplication  $OthersLeaveApplication
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete()
    {
        return false;
    }
}
