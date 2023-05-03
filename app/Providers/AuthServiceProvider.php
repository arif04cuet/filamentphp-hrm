<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\AttendanceList;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Doptor;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Policies\AttendanceListPolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\DesignationPolicy;
use App\Policies\DoptorPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Doptor::class => DoptorPolicy::class,
        Department::class => DepartmentPolicy::class,
        Designation::class => DesignationPolicy::class,
        Permission::class => PermissionPolicy::class,
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
        AttendanceList::class => AttendanceListPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
