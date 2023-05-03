<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\DoptorAbleTrait;
use Illuminate\Http\Request;
use App\Models\Scopes\DoptorAbleScope;
use Illuminate\Support\Facades\DB;

class LeaveCause extends Model
{
    use HasFactory, SoftDeletes, DoptorAbleTrait;

    const A_LEAVE_STATUS = 'এককালীন';
    const B_LEAVE_STATUS = 'সর্বোচ্চ';
    const C_LEAVE_STATUS = '-';

    const DAY = 'Day';
    const MONTH = 'Month';
    const YEAR = 'Year';
    const OPTION_D = 'সুপারিশ যতদিন';
    const OPTION_E = 'অনুমোদনকৃত';
    const OPTION_F = 'সরকার ঘোষিত';
    const OPTION_G = 'মেয়াদোত্তীর্ণ ছুটি দিন সমূহ';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'leave_details';

    /**
     * The name that will be used when log this model. (optional)
     *
     * @var bool
     */
    protected static $logName = 'leave_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['leave_type_id', 'leave_cause', 'per_date_type', 'per_leave_days', 'temp_date_type', 'temp_leave_days', 'leave_status', 'validation_json', 'attachment_json', 'validation', 'attachment'];

    protected $casts = [
        'validation_json' => 'json',
        'attachment_json' => 'json',
        'validation' => 'array',
        'attachment' => 'array',
    ];

    public static function leave_status($key = null)
    {
        $statuses = [
            self::A_LEAVE_STATUS => 'এককালীন',
            self::B_LEAVE_STATUS => 'সর্বোচ্চ',
            self::C_LEAVE_STATUS => '-',
        ];

        if (!is_null($key) && isset($statuses[$key]))
            return $statuses[$key];

        return $statuses;
    }

    public static function day_type($key = null)
    {
        $days = [
            self::DAY => 'দিন',
            self::MONTH => 'মাস',
            self::YEAR => 'বছর',
            self::OPTION_D => 'সুপারিশ যতদিন',
            self::OPTION_E => 'অনুমোদনকৃত',
            self::OPTION_F => 'সরকার ঘোষিত',
            self::OPTION_G => 'মেয়াদোত্তীর্ণ ছুটি দিন সমূহ',
        ];

        if (!is_null($key) && isset($days[$key]))
            return $days[$key];

        return $days;
    }

    public function leave_type()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function totalDays($leave_type_id)
    {
        $leave = LeaveCause::where('id', $leave_type_id)->first();
        if ($leave && auth()->user()->employee) {
            $dateType = auth()->user()->employee->is_permanent == 1 ? $leave->per_date_type : $leave->temp_date_type;
            if (auth()->user()->employee->is_permanent == 1)
                $leaveDays = $dateType === 'Day' ? $leave->per_leave_days : ($dateType === 'Month' ? $leave->per_leave_days * 30 : ($dateType === 'Year' ? $leave->per_leave_days * 365 : $dateType));
            else
                $leaveDays = $dateType === 'Day' ? $leave->temp_leave_days : ($dateType === 'Month' ? $leave->temp_leave_days * 30 : ($dateType === 'Year' ? $leave->temp_leave_days * 365 : $dateType));
        }
        return $leaveDays ?? 0;
    }

    public function usedDays($leave_type_id, $emp_id)
    {
        if ($emp_id != 0) {
            $application = LeaveApplication::where('employee_id', $emp_id)->where('leave_cause_id', $leave_type_id)->where('status', 'Approved')->count();
            return $application;
        } else
            return 0;
    }

    public function availableDays($leave_type_id, $emp_id)
    {
        if ($emp_id != 0) {
            $total = LeaveCause::totalDays($leave_type_id);
            $used = LeaveCause::usedDays($leave_type_id, $emp_id);
            $available = is_numeric($total) ? $total - $used : $total;
            return $available;
        } else
            return 0;
    }

    public function apiLeaveBalances(Request $request)
    {
        $leaves = LeaveCause::withoutGlobalScope(DoptorAbleScope::class)
            ->join('leave_types', 'leave_types.id', '=', 'leave_details.leave_type_id')
            ->where('leave_types.doptor_id', function ($query) use ($request) {
                $query->select('doptor_id')->from('employees')->where('id', $request->id);
            })
            ->select('leave_details.*', DB::raw("(SELECT is_permanent FROM employees WHERE employees.id = $request->id) as is_permanent"))->get();
        $balances = [];
        if ($leaves) {
            foreach ($leaves as $key => $leave) {
                $dateType = $leave->is_permanent == 1 ? $leave->per_date_type : $leave->temp_date_type;
                if ($leave->is_permanent == 1)
                    $leaveDays = $dateType === 'Day' ? $leave->per_leave_days : ($dateType === 'Month' ? $leave->per_leave_days * 30 : ($dateType === 'Year' ? $leave->per_leave_days * 365 : $dateType));
                else
                    $leaveDays = $dateType === 'Day' ? $leave->temp_leave_days : ($dateType === 'Month' ? $leave->temp_leave_days * 30 : ($dateType === 'Year' ? $leave->temp_leave_days * 365 : $dateType));
                $balances[$key]['leave_id'] = $leave->id;
                $balances[$key]['leave_cause'] = $leave->leave_cause;
                $balances[$key]['total'] = $leaveDays;
                $balances[$key]['used'] = LeaveApplication::withoutGlobalScope(DoptorAbleScope::class)->where('employee_id', $request->id)->where('leave_cause_id', $leave->id)->where('status', 'Approved')->count();
                $balances[$key]['available'] = is_numeric($leaveDays) ? $leaveDays - $balances[$key]['used'] : $leaveDays;
            }
        }
        return $balances;
    }
}
