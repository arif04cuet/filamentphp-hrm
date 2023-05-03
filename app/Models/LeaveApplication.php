<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DoptorAbleTrait;
use Illuminate\Support\Facades\DB;


class LeaveApplication extends Model
{
    use HasFactory, DoptorAbleTrait;

    protected $fillable = ['employee_id', 'doptor_id', 'leave_type_id', 'leave_cause_id', 'leave_from', 'leave_to', 'total_leave_days', 'address_during_leave', 'attachment', 'status', 'apply_to', 'approved_by'];

    protected $casts = [
        'attachment' => 'array',
    ];

    const A_PENDING = 'Pending';
    const B_APPROVED = 'Approved';
    const C_REJECTED = 'Rejected';
    const D_CANCELED = 'Canceled';

    public static function statusType($key = null)
    {
        $types = [
            self::A_PENDING => 'Pending',
            self::B_APPROVED => 'Approve',
            self::C_REJECTED => 'Reject',
            self::D_CANCELED => 'Cancel',
        ];

        if (!is_null($key) && isset($types[$key]))
            return $types[$key];

        return $types;
    }

    public function doptor()
    {
        return $this->belongsTo(Doptor::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function leaveCause()
    {
        return $this->belongsTo(LeaveCause::class);
    }

    public function applyTo()
    {
        return $this->belongsTo(Employee::class, 'apply_to', 'id');
    }

    public static function leaveCauseByType($id)
    {
        return LeaveCause::where('leave_type_id', $id)->pluck('leave_cause', 'id');
    }

    public static function attachmentLabel($type, $cause)
    {
        return LeaveCause::where('leave_type_id', $type)->where('id', $cause)->pluck('attachment');
    }

    public static function userEmpForApply($des_id, $dop_id)
    {
        $employees = DB::table('employees')
            ->join('users', 'employees.gov_id', '=', 'users.username')
            ->where('users.designation_id', '!=', $des_id)
            ->where('employees.doptor_id', $dop_id)
            ->pluck('employees.name_bn', 'employees.id');
        return $employees;
    }
}
