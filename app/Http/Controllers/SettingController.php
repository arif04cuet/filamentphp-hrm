<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Permission, Role, User, Designation, Department};

class SettingController extends Controller
{
    public function role()
    {
        $roles = Role::with('permission')->get();
        return view("settings.role", compact('roles'));
    }

    public function permission()
    {
        $permissions = Permission::paginate(10);
        return view("settings.permission", compact('permissions'));
    }

    public function designation()
    {
        $designations = Designation::paginate(10);
        return view("settings.designation", compact('designations'));
    }

    public function department()
    {
        $departments = Department::paginate(10);
        return view("settings.department", compact('departments'));
    }

    public function user()
    {
        $users = User::paginate(10);
        return view("settings.user", compact('users'));
    }
}
