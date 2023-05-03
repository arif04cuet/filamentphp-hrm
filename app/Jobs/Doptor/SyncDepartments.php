<?php

namespace App\Jobs\Doptor;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use App\Models\Department;
use App\Facades\IDP;
use App\Models\Designation as EntitiesDesignation;

class SyncDepartments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $doptor;
    public function __construct($doptor)
    {
        $this->doptor = $doptor;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $doptor = $this->doptor;

        $response = IDP::get('/organogram/office/' . $doptor->id . '/units');

        $items = $response
            ->collect()
            ->filter(function ($item) {
                return isset($item['name']['bn']) && !empty($item['name']['bn']);
            })
            ->map(function ($unit) use ($doptor) {
                $name_en = isset($unit['name']['en']) ? $unit['name']['en'] : '';
                $name_bn = isset($unit['name']['bn']) ? $unit['name']['bn'] : '';
                return  [
                    'id' => $unit['id'],
                    'doptor_id' => $doptor->id,
                    'name_bn' => $name_bn,
                    'name_en' => $name_en,
                    'department_code' => strtoupper(str_slug($name_bn)),
                    'dashboard_unit_id' => 0
                ];
            })
            ->toArray();

        Department::upsert($items, ['doptor_id']);
    }
}