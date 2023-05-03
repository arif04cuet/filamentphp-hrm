<?php

namespace App\Jobs\Doptor;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Designation;
use App\Models\Employee;
use App\Facades\IDP;
// use Illuminate\Contracts\Queue\ShouldBeUnique;
// use Illuminate\Support\Facades\Http;
// use Modules\HRM\Entities\Department;
// use Modules\HRM\Entities\Designation as EntitiesDesignation;

class SyncEmployees implements ShouldQueue
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
        if ($doptor = $this->doptor) {

            // $api_url = '/organogram/office/' . $doptor->id . '/employees';
            // $response = Http::dashboard()->get($api_url);

            $response = IDP::get('/organogram/office/' . $doptor->id . '/employees');

            $employees = $response
                ->collect()
                ->filter(function ($item) {
                    return isset($item['id']) && isset($item['name']['bn']) && !empty($item['name']['bn']);
                })
                ->each(function ($item) use ($doptor) {

                    $employee = Employee::updateOrCreate(
                        [
                            'id'   => $item['id'],
                        ],
                        [
                            'name_bn' => isset($item['name']['bn']) ? $item['name']['bn'] : '',
                            'name_en' => isset($item['name']['en']) ? $item['name']['en'] : '',
                            'email' => $item['email'] ? $item['email'] : '',
                            'gender' => isset($item['gender']) ? $item['gender'] : '',
                            'religion' => isset($item['religion']) ? $item['religion'] : '',
                            'tel_office' => isset($item['tel_office']) ? $item['tel_office'] : '',
                            'tel_home' => isset($item['tel_home']) ? $item['tel_home'] : '',
                            'mobile_office' => $item['mobile'] ? $item['mobile'] : '',
                            'mobile_home' => isset($item['mobile_two']) ? $item['mobile_two'] : '',
                            'photo' => $item['photo'] ? $item['photo'] : '',
                            'signature' => $item['signature'] ? $item['signature'] : '',
                            'doptor_id' => $doptor->id,
                            'gov_id' => $item['username'] ? $item['username'] : '',
                            'is_permanent' => $item['is_permanent'] ? $item['is_permanent'] : '0'
                        ]
                    );

                    $designation_id = $item['designation']['id'];
                    $designation = Designation::withoutGlobalScope(DoptorAbleScope::class)->find($designation_id);
                    if ($designation) {
                        $designation->update(['employee_id' => $item['id']]);
                    }
                });
        }
    }
}