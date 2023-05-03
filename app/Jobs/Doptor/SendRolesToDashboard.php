<?php

namespace App\Jobs\Doptor;

use App\Facades\IDP;
use App\Models\Role;
use App\Models\SpatieRole;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendRolesToDashboard
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $rules;
    public function __construct($rules)
    {
        $this->rules = $rules;
    }


    public function handle()
    {

        $rules = $this->rules instanceof Role ? collect([$this->rules]) : $this->rules;

        $data['roles'] = $rules
            ->map(function ($role) {

                $formatedRole = [
                    "module_role_id" => $role->id,
                    "name" => $role->name
                ];

                if ($role->doptor_id)
                    $formatedRole["doptor_id"] = $role->doptor->doptor_id;

                return $formatedRole;
            })
            ->toArray();

        if ($data) {
            $response = IDP::post('/roles', $data);
        }
    }
}
