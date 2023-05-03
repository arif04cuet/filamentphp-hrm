<?php

namespace App\Jobs\Doptor;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use App\Facades\IDP;
use App\Models\Designation;

class SyncDesignations implements ShouldQueue
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
            $response = IDP::get('/organogram/office/' . $doptor->id . '/designations');
            //
            $response->collect()
                ->filter(function ($item) {
                    return isset($item['id']) && isset($item['name']['bn']) && !empty($item['name']['bn']);
                })
                ->each(function ($item) use ($doptor) {
                    $name_en = isset($item['name']['en']) ? $item['name']['en'] : '';
                    $name_bn = isset($item['name']['bn']) ? $item['name']['bn'] : '';

                    $designation = Designation::updateOrCreate(
                        [
                            'id'   => $item['id'],
                        ],
                        [
                            'doptor_id' => $doptor->id,
                            'name_en' => $name_en,
                            'name_bn' => $name_bn,
                            'short_name' => $name_en ? $name_en : $name_bn,
                            'department_id' => $item['unit']['id'],
                        ]
                    );
                });
        }
    }
}