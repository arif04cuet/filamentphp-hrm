<?php

namespace App\Jobs\Doptor;

use App\Facades\IDP;
use App\Models\SpatieRole;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class DeleteRoleFromDashboard
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $roleIds;
    public function __construct(array $roleIds)
    {
        $this->roleIds = $roleIds;
    }


    public function handle()
    {

        $data['module_role_ids'] = $this->roleIds;

        IDP::post('/roles/destroy', $data);
    }
}
