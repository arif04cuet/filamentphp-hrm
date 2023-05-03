<?php

namespace App\Filament\Resources\AttendanceListResource\Pages;

use App\Filament\Resources\AttendanceListResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAttendanceLists extends ListRecords
{
    protected static string $resource = AttendanceListResource::class;

    protected function getActions(): array
    {
        return [];
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [25, 50, 100];
    }
}
