<?php

namespace App\Http\Controllers\Auth;

use App\Events\AttendanceHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Facades\IDP;
use App\Models\Doptor;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    public function login(Request $request, $token = null)
    {
        $token = request()->token ?? $token;

        $user_request = IDP::user($token);

        $dashboard_url = config('services.dashboard.login_url');

        if (!$user_request->successful())
            return redirect()->to($dashboard_url . "?error=Something went wrong!");

        $dashboard_user = $user_request->json();

        $clientId = config('services.dashboard.client_id');
        $roles    = isset($dashboard_user['roles'][$clientId]) ? $dashboard_user['roles'][$clientId] : [];

        if (empty($roles))
            return redirect()->to($dashboard_url . "?error=Role missings!");

        if (!isset($dashboard_user['office']['id']))
            return redirect()->to($dashboard_url . "?error=Office not found!");


        //superadmin login
        if (collect($roles)->pluck('name')->contains('SuperAdmin'))
            return $this->doLoginForSuperAdmin($request, $dashboard_user, $roles, $token);


        $employee = Employee::withoutGlobalScopes()
            ->where('id', $dashboard_user['employee']['id'])
            ->first();
        $doptor = Doptor::find($dashboard_user['office']['id']);

        if (!$employee || !$doptor)
            return redirect()->to($dashboard_url . "?error=User Employee/Doptor is not valid!!");

        $username = $dashboard_user['username'];
        $user = User::withoutGlobalScopes()
            ->updateOrCreate(
                ['username' => $username],
                [
                    "name" => $dashboard_user['name'],
                    "email" => $dashboard_user['email'],
                    "mobile" => $dashboard_user['mobile'],
                    "password" => Hash::make(123456),
                    'office_id' => $dashboard_user['office']['id']
                ]
            );


        //autometically log user in
        if ($user) {
            return $this->loginAndRedirect($request, $user, $roles, $token);
        }
        return redirect()->to($dashboard_url . "?error=Something went wrong!");
    }

    // log out
    public function logout(Request $request, $redirect_url = null)
    {
        \Session::flush();
        \Auth::logout();
        return redirect()->to($request->redirect_url);
    }



    public function doLoginForSuperAdmin($request, $dashboard_user, $roles, $token)
    {
        $user = User::withoutGlobalScopes()->find(1);
        if ($user) {
            $superadmin = tap($user)->update([
                'name' => $dashboard_user['name'],
                'email' => $dashboard_user['email'],
                'doptor_id' => 0
            ]);

            return $this->loginAndRedirect($request, $superadmin, $roles, $token);
        }
    }




    public function loginAndRedirect($request, $user, $roles, $token)
    {
        //update user roles
        $roleIds = collect($roles)->pluck('component_role_id')->toArray();
        $user->roles()->sync($roleIds);

        if (Auth::check())
            auth()->guard()->logout();

        Auth::login($user);
        $request->session()->regenerate();
        session(['dashboard_user_token' => $token]);

        event(new AttendanceHistory($user));

        if ($doptor = $user->doptor)
            $this->addDoptorDataToSession($doptor);
            
        if ($url = $request->redirect)
            return redirect()->to($url);
        else
            return redirect()->to('/admin');
    }

    public function addDoptorDataToSession($doptor)
    {
        $doptorData = [
            'id' => $doptor->id,
            'name_bng' => $doptor->name_bng,
            'name_eng' => $doptor->name_eng,
        ];
        session()->put('user_doptor', $doptorData);
    }
}
