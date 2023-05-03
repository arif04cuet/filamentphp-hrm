<?php

namespace App\Filament\Widgets;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Doptor;
use App\Models\Employee;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getCards(): array
    {
        $office = count(Doptor::all());
        $dep = count(Department::all());
        $des = count(Designation::all());
        $emp = count(Employee::all());
        return [
            // Card::make('Offices', 1)->url('admin/doptors'),
            // Card::make('Departments', $dep)->url('admin/departments'),
            // Card::make('Designations', $des)->url('admin/designations'),
            // Card::make('Employees', $emp)->url('admin/employees'),
        ];
    }
}