<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doptor extends Model
{
    use HasFactory;

    protected $fillable = ["id", "name_en", "name_bn"];

    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    public function doptorSetting()
    {
        return $this->hasOne(DoptorSetting::class);
    }

    public function doptorTime()
    {
        return $this->hasMany(OfficeTime::class);
    }
}
