<?php

namespace App\Models;

use App\Traits\DoptorAbleTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class OfficeTime extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'office_times';

    /**
     * The name that will be used when log this model. (optional)
     *
     * @var bool
     */
    protected static $logName = 'office_times';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['from_date', 'to_date', 'from_time', 'to_time', 'status', 'doptor_id'];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * If to_date is null return current date.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function toDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value == null ? \Carbon\Carbon::now()->format('Y-m-j') : $value,
        );
    }

    public function doptorSetting()
    {
        return $this->hasOne(DoptorSetting::class, 'doptor_id', 'doptor_id');
    }

    public function officeTime($id)
    {
        $times = OfficeTime::where('doptor_id', $id)->where('status', 1)->get();
        $string = '';
        foreach ($times as $time) {
            $row = \Carbon\Carbon::parse($time['from_time'])->format('g:i A') . ' to ' . \Carbon\Carbon::parse($time['to_time'])->format('g:i A') . ' .';
            $string = $string . ' ' . $row;
        }

        return $string;
    }
}
