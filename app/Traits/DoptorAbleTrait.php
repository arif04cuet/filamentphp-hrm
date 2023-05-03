<?php

/**
 * Created by VS.
 * User: Araf
 * Date: 03/04/22
 * Time: 5:39 PM
 */

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Scopes\DoptorAbleScope;
use App\Models\SpatieRole;

trait DoptorAbleTrait
{

    public static function bootDoptorAbleTrait()
    {
        static::creating(function ($model) {
            $doptorId = isset($model->doptor_id) ? $model->doptor_id : auth()->user()->office_id;
            $model->doptor_id = $doptorId;
        });

        static::addGlobalScope(new DoptorAbleScope);
    }
}