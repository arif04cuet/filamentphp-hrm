<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'mobile',
        'office_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //Relations

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'username', 'gov_id');
    }


    public function canAccessFilament(): bool
    {
        return true;
    }

    /*
     * ********************
     * 
     *
    */
    static function IDPUser($user = null)
    {
        if (!isset($user['message']) || $user['message'] != 'Unauthenticated.') {
            $user = collect($user);
            $data = $user->only('mobile', 'email', 'name', 'username', 'cdap_id')->toArray();
            //
            $data['office_id']      = ($user['office']['id'] ?? 0);
            $data['designation_id'] = ($user['employee']['designation']['id'] ?? 0);
            $data['cdap_id']        = $data['cdap_id'] ?? 0;
            $data['password']       = Hash::make(123456);
            //
            $loc_user = User::updateOrCreate($user->only('username')->toArray(), $data);
            //
            if (isset($user['roles'][8])) {
                $role_ids = collect($user['roles'][8])->pluck('component_role_id')->toArray();
                if (Role::whereIn('id', $role_ids)->exists())
                    $loc_user->assignRole($role_ids);
            }

            return $loc_user;
        } else return false;
    }

    static function isAdmin($user)
    {
        if ($user->office_id == 0)
            return true;
        else
            return false;
    }

    public function doptor()
    {
        return $this->belongsTo(Doptor::class, 'office_id', 'id');
    }
}
