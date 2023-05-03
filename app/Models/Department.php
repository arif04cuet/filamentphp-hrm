<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DoptorAbleTrait;

class Department extends Model
{
    use HasFactory, DoptorAbleTrait;

    protected $fillable = ['id', 'name_bn', 'name_en', 'department_code', 'dashboard_unit_id', 'doptor_id'];


    /*
     * ****************
     * RELATION WITH 
     * DOPTOR
     * ************** */
    public function doptor(){
        return $this->hasOne(Doptor::class, 'id', 'doptor_id');
    }
}
