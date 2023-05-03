<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DoptorAbleTrait;

class Designation extends Model
{
    use HasFactory, DoptorAbleTrait;


    protected $fillable = ['id', 'name_en', 'name_bn', 'short_name', 'department_id', 'doptor_id', 'employee_id'];


    /*
     * ****************
     * RELATION WITH 
     * DOPTOR
     * ************** */
    public function doptor()
    {
        return $this->hasOne(Doptor::class, 'id', 'doptor_id');
    }

    /*
     * ****************
     * RELATION WITH 
     * DEPARTMENT
     * ************** */
    public function department()
    {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }

    /*
     * ****************
     * RELATION WITH 
     * EMPLOYEE
     * ************** */
    public function employee()
    {
        return $this->hasOne(Employee::class, 'id', 'employee_id');
    }
}