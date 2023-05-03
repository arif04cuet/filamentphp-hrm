<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DoptorAbleTrait;

class Employee extends Model
{
    use HasFactory, DoptorAbleTrait;

    protected $fillable = ['id', 'name_en', 'name_bn', 'email', 'gender', 'religion', 'tel_office', 'tel_home', 'mobile_office', 'mobile_home', 'photo', 'signature', 'doptor_id', 'gov_id', 'is_permanent'];

    /*
     * ****************
     * RELATION WITH 
     * DOPTOR
     * ************** */
    public function doptor()
    {
        return $this->hasOne(Doptor::class, 'id', 'doptor_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'username', 'gov_id');
    }

    public function designation()
    {
        return $this->hasOne(Designation::class);
    }

    public function employeeNameById($id)
    {
        return Employee::where('id', $id)->pluck('name_bn')->first();
    }

    public static function optionsNameDes()
    {
        $employees = Employee::where('id', '!=', auth()->user()->employee ? auth()->user()->employee->id : 0)->get();
        foreach ($employees as $employee) {
            $employee->option = $employee->designation ? $employee->name_bn . ' [' .  $employee->designation->name_bn . ']' : $employee->name_bn;
        }
        $options = $employees->pluck('option', 'id');
        return $options;
    }

    public function leaveApplications()
    {
        return $this->hasMany(LeaveApplication::class);
    }
}
