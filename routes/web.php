<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\{HomeController, SettingController, OfficeController};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(["prefix" => "admin", "middleware" => "auth"], function () {
    /*
     * *********************
     * BASIC USER ROUTES
     * *******************/
    Route::group(["controller" => HomeController::class], function () {
        Route::get("dashboard", "index")->name('admin.dashboard');
        //
    });


    /*
     * ***********************
     * APP SETTINGS MODULE
     * *********************/
    Route::group(["prefix" => "settings", "controller" => SettingController::class], function () {

        /*
         * ***********************
         * APP OFFICE MODULE
         * *********************/
        Route::group(["prefix" => "office", "controller" => OfficeController::class], function () {
            Route::get("list", "index")->name('admin.settings.office.list');
            Route::get("add",  "create")->name('admin.settings.office.add');
            Route::post("store",  "store")->name('admin.settings.office.store');
            Route::get("get-office-list-by-layers/{id?}",  "getOfficeListByLayers")->name('admin.settings.office.layered_office');
            Route::get("get-office-uni-by-office/{id?}",  "getOfficeUnitsByOffice")->name('admin.settings.office.office_unit');
            Route::get('/sync/{id}', "sync");
        });


        /*
         * ***********************
         * APP BASIC MODULE
         * *********************/
        Route::get("designations", "designation")->name('admin.settings.designations');
        Route::get("departments", "department")->name('admin.settings.departments');
        Route::get("roles", "role")->name('admin.settings.roles');
        Route::get("permissions", "permission")->name('admin.settings.permissions');
        Route::get("users", "user")->name('admin.setting.users');
    });

    Route::post('logout', [LoginController::class, 'logout'])->name('admin.logout');
});

Route::get('sso/login/{token?}', [LoginController::class, 'login'])->name('admin.login');



Route::get('/', function () {
    // dd(App\Models\User::create([
    //     'name'=>'Shamim Haque', 
    //     'email'=>'shamim.haque.dev@gmail.com', 
    //     'username' => 'username',
    //     'cdap_id' => 0,
    //     'mobile' => '2323453345',
    //     'office_id' => 77,
    //     'designation_id' => 22,
    //     'password'=> (\Hash::make('123456'))
    // ]));

    return redirect()->route('filament.pages.dashboard');

    dd(App\Models\Role::create(['name' => 'Superadmin', 'is_system' => 1, 'doptor_id' => 340]));
    /*=============================================================*/
    $user = new App\Models\User();

    \Auth::guard('web')->attempt(['email' => 'shamim.haque.dev@gmail.com', 'password' => '123456']);
    return redirect()->route('filament.pages.dashboard');

    dd($user::first()->assignRole('Superadmin'));


    /*==============================================================*/
    $user::create(['name' => 'Shamim Haque', 'email' => 'shamim.haque.dev@gmail.com', 'password' => \Hash::make('123456')]);

    /*==============================================================*/
    $permissions = App\Models\Permission::get();
    $role = App\Models\Role::first();

    dd($role->syncPermissions($permissions));



    /*==============================================================*/
    $permissions = App\Models\Permission::defaultPermissions();

    foreach ($permissions as $perms) {
        App\Models\Permission::firstOrCreate(['name' => $perms]);
    }
    dd('Done');

    /*===============================================================*/
    dd(App\Models\Role::create(['name' => 'Superadmin']));


    return redirect()->route('filament.pages.dashboard');
    // return view('welcome');
});

Route::post('/searchAttendance/{ym}', [App\Models\AttendanceList::class, 'searchAttendance'])->name('search-attendance');
Route::get('/setcause', [App\Models\AttendanceList::class, 'setcause'])->name('setcause');

// Route::post('/filament-logout', function () {
//     return redirect('http://172.168.0.179:8000/admin/login');
// })->name('test');

Route::get('/apiGetGrades', [App\Models\Grade::class, 'getGrades']);