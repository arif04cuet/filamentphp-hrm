<?php

namespace App\Models;

use App\Jobs\Doptor\DeleteRoleFromDashboard;
use App\Jobs\Doptor\SendRolesToDashboard;
use Filament\Facades\Filament;
use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Support\Str;

class Role extends SpatieRole
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->mergeFillable(['name', 'is_system', 'doptor_id']);
    }


    protected static function booted()
    {
        static::creating(function ($model) {
            $model->doptor_id = 0;
        });

        static::created(function ($role) {
            SendRolesToDashboard::dispatch($role);
        });

        static::updated(function ($role) {
            SendRolesToDashboard::dispatch($role);
        });

        static::deleting(function ($role) {
            DeleteRoleFromDashboard::dispatch([$role->id]);
        });
    }


    public function doptor()
    {
        return $this->belongsTo(Doptor::class);
    }

    public static function getEntities()
    {
        return collect(Filament::getResources())
            ->merge(self::getSlugPermissions())
            ->unique()
            ->reduce(function ($options, $resource) {
                $option = Str::before(Str::afterLast($resource, '\\'), 'Resource');
                $options[$option] = $option;
                return $options;
            }, []);
    }

    public static function getSlugPermissions(): array
    {
        return collect(config('filament-permission.permissions', []))
            ->flatten()
            ->values()
            ->map(function (string $action) {
                return Str::slug($action, '_');
            })
            ->all();
    }

    public static function getPermissions(): array
    {
        return ['view', 'viewAny', 'create', 'delete', 'deleteAny', 'update'];
    }
}
