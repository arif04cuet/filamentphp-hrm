<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use App\Models\Department;
use App\Models\Employee;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEmployees extends ViewRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $employee = Employee::find($data['id']);
        $data['doptor'] = $employee->doptor->name_bn;
        if ($employee->designation) {
            $dep = Department::find($employee->designation->department_id);
            $data['department'] = $dep->name_bn;
            $data['designation'] = $employee->designation->name_bn;
        } else {
            $data['department'] = '';
            $data['designation'] = '';
        }
        return $data;
    }
}
