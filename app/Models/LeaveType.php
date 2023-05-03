<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\DoptorAbleTrait;

class LeaveType extends Model
{
    use HasFactory, SoftDeletes, DoptorAbleTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'leave_types';

    /**
     * The name that will be used when log this model. (optional)
     *
     * @var bool
     */
    protected static $logName = 'leave_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];
}
