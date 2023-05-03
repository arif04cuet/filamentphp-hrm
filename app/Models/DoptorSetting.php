<?php

namespace App\Models;

use App\Traits\DoptorAbleTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoptorSetting extends Model
{
    use HasFactory, DoptorAbleTrait, SoftDeletes;

    protected $fillable = ["weekend", "ips", "whitelisted_ips", "ips_active_inactive", "doptor_id"];

    protected $casts = [
        'weekend' => 'array',
        'ips' => 'json',
        'whitelisted_ips' => 'array',
        'ips_active_inactive' => 'array',
    ];

    public function doptor()
    {
        return $this->belongsTo(Doptor::class);
    }

    public function officeTimes()
    {
        return $this->hasMany(OfficeTime::class, 'doptor_id', 'doptor_id');
    }
}