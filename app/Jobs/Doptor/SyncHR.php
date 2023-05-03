<?php

namespace App\Jobs\Doptor;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncHR implements ShouldQueue
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
        Department::where('doptor_id', $doptor->id)->delete();
        SyncDepartments::dispatch($doptor);
        Designation::where('doptor_id', $doptor->id)->delete();
        SyncDesignations::dispatch($doptor);
        Employee::where('doptor_id', $doptor->id)->delete();
        SyncEmployees::dispatch($doptor);
    }
}