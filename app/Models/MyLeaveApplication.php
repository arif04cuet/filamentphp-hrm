<?php

namespace App\Models;

use App\Models\LeaveApplication;

class MyLeaveApplication extends LeaveApplication
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'leave_applications';
}
