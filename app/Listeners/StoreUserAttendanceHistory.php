<?php

namespace App\Listeners;

use App\Events\AttendanceHistory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\AttendanceList;
use App\Models\DoptorSetting;
use App\Models\Holiday;

class StoreUserAttendanceHistory
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\AttendanceHistory  $event
     * @return void
     */
    public function handle(AttendanceHistory $event)
    {
        $user = $event->user;
        // dd($this->getIp());

        if ($user) {
            if ($user->employee) { // If doptor on board
                $current_date = Carbon::now()->format('Y-m-j');
                $currentTime = Carbon::now('GMT+6')->format('H:i:s');
                $employeeEntry = AttendanceList::where('date', $current_date)->where('employee_id', $user->employee->id)->first();
                if (!$employeeEntry) { // Attendance list entry check

                    $match = [
                        'weekend' => false,
                        'ip_match' => false,
                        'holiday_match' => false,
                    ];

                    $doptorSetting = DoptorSetting::where('doptor_id', $user->employee->doptor_id)->first();
                    if ($doptorSetting) { //Doptor setting check

                        // IP Match
                        // $loginIp = "127.0.0.1";
                        $loginIp = $this->getIp();
                        foreach ($doptorSetting->ips as $ip) {
                            if ($ip['status'] == true && $ip['ip'] == $loginIp) {
                                $match['ip_match'] = true;
                                break;
                            }
                        }

                        // Weekend Match
                        $weekend = $doptorSetting->weekend;
                        $today = Carbon::now()->format('D');
                        foreach ($weekend as $day) {
                            if ($day == $today) {
                                $match['weekend'] = true;
                                break;
                            }
                        }

                        // Holiday Match
                        $holiday = Holiday::where('date_from', $current_date)->first();
                        if ($holiday)
                            $match['holiday_match'] = true;

                        //Attendance
                        if ($match['ip_match'] == true && $match['holiday_match'] == false && $match['weekend'] == false) {

                            // Status Check
                            $doptor_times = $user->employee->doptor->doptorTime;
                            $status = null;
                            $late_flag = 0;
                            foreach ($doptor_times as $times) {

                                $startTime = $times->from_time;
                                $endTime = $times->to_time;

                                $currentDate = date('Y-m-d', strtotime($current_date));
                                $startDate = date('Y-m-d', strtotime($times->from_date));
                                $endDate = date('Y-m-d', strtotime($times->to_date));

                                if (($currentDate >= $startDate) && ($currentDate <= $endDate) && ($currentTime <= $startTime) && ($times->status == true)) {
                                    $status = AttendanceList::B_PRESENT;
                                    break;
                                } else if (($currentDate >= $startDate) && ($currentDate <= $endDate) && ($currentTime >= $startTime) && ($currentTime <= $endTime) && ($times->status == true)) {
                                    $status = AttendanceList::C_LATE;
                                    $late_flag = 1;
                                    break;
                                }
                            }

                            if ($status) {
                                // Attendance entry
                                $attendance = AttendanceList::create([
                                    "employee_id" => $user->employee->id,
                                    "ip" => $loginIp,
                                    "date" => $current_date,
                                    "entry_time" => $currentTime,
                                    "status" => $status,
                                    "late_flag" => $late_flag,
                                    "doptor_id" => $user->employee->doptor_id,
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }

    // ip
    public function getIp()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return request()->ip(); // it will return server ip when no client ip found
    }
}
