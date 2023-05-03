<?php

namespace App\Models;

use App\Traits\DoptorAbleTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceList extends Model
{
    use HasFactory, DoptorAbleTrait;

    const A_ABSENT = 'ABSENT';
    const B_PRESENT = 'PRESENT';
    const C_LATE = 'LATE';

    protected $fillable = ['employee_id', 'ip', 'date', 'entry_time', 'status', 'leave_time', 'late_flag', 'late_cause', 'approved_at', 'doptor_id'];

    protected $casts = [
        'late_flag' => 'boolean',
    ];

    public static function attendanceType($key = null)
    {
        $attendances = [
            self::A_ABSENT => 'ABSENT',
            self::B_PRESENT => 'PRESENT',
            self::C_LATE => 'LATE',
        ];

        if (!is_null($key) && isset($attendances[$key]))
            return $attendances[$key];

        return $attendances;
    }

    public static function searchAttendance(Request $request)
    {
        // $year_month = explode('-', $request->ymd);
        // $year = $year_month['0'];
        // $month = $year_month['1'];

        $employee_id = auth()->user()->employee->id;
        $doptor_id = auth()->user()->employee->doptor_id;

        $start = Carbon::parse($request->ym)->startOfMonth()->format('Y-m-d');
        $end = Carbon::parse($request->ym)->endOfMonth()->format('Y-m-d');
        $currentDate = Carbon::now()->format('Y-m-d');

        // all dates for month in dates[]
        $dates = [];
        for ($i = $start; $i <= $end; $i++) {
            $dates[] = $i;
        }

        $records = [
            'empty_cell' => AttendanceList::dayColors('empty_cell'),
            'late_cell' => AttendanceList::dayColors('late_cell'),
        ];

        foreach ($dates as $date) {
            $list = AttendanceList::where('employee_id', $employee_id)->where('date', $date)->first();
            if ($list) {
                $records['statuses'][] = $list->status;
                $records['causes'][] = $list->late_cause;
                $records['times'][] = \Carbon\Carbon::parse($list->entry_time)->format('h:i');
                $records['approves'][] = $list->approved_at;
                $records['colors'][] = AttendanceList::dayColors('default_cell');
            } else {

                // weekend status
                $weekend = false;
                $doptorSetting = DoptorSetting::where('doptor_id', $doptor_id)->first();
                $today = Carbon::parse($date)->format('D');
                if ($doptorSetting) {
                    foreach ($doptorSetting->weekend as $day) {
                        if ($day == $today) {
                            $weekend = true;
                            break;
                        }
                    }
                }

                // holiday status
                $holiday = Holiday::where('date_from', $date)->first();
                $records['causes'][] = "";
                $records['times'][] = "";
                $records['approves'][] = "";

                // status conditions
                if ($holiday) {
                    $records['statuses'][] = $holiday->holidayName->title;
                    $records['colors'][] = AttendanceList::dayColors('holiday_cell');
                } else if ($weekend) {
                    $records['statuses'][] = "WEEKEND";
                    $records['colors'][] = AttendanceList::dayColors('weekend_cell');
                } else if ($currentDate >= $date && !$holiday) {
                    $records['statuses'][] = "ABSENT";
                    $records['colors'][] = AttendanceList::dayColors('absent_cell');
                } else {
                    $records['statuses'][] = "";
                    $records['colors'][] = AttendanceList::dayColors('default_cell');
                }
            }
        }
        return $records;
    }

    // cell color set
    public static function dayColors($key = null)
    {
        $colors = [
            'default_cell' => "transparent",
            'weekend_cell' => "rgb(255, 165, 164)",
            'holiday_cell' => "rgb(255, 165, 164)",
            'absent_cell' => "transparent",
            'empty_cell' => 'rgb(230, 230, 230)',
            'late_cell' => 'rgb(255, 212, 140)',
        ];

        if (!is_null($key) && isset($colors[$key]))
            return $colors[$key];

        return $colors;
    }

    public static function setcause(Request $request)
    {
        $employee_id = auth()->user()->employee->id;
        $current = Carbon::now('GMT+6')->format('d-m-Y H:i:s');

        $match = AttendanceList::where('employee_id', $employee_id)->where('date', $request->date)->first();
        $newtext = " ( " . $current . " )  " . $request->late_cause;

        if ($match) {
            $match->update(['late_cause' => $newtext]);
        }
        return back();
    }

    public static function approve($id)
    {
        $match = AttendanceList::where('id', $id)->where('late_flag', 1)->where('approved_at', null)->first();
        $result = '';
        if ($match)
            $result = 'No';
        return $result;
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
