<?php

namespace App\Filament\Resources\OthersLeaveApplicationResource\Pages;

use App\Filament\Resources\OthersLeaveApplicationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Closure;
use Illuminate\Database\Eloquent\Model;

class ListOthersLeaveApplications extends ListRecords
{
    protected static string $resource = OthersLeaveApplicationResource::class;

    protected function getActions(): array
    {
        return [];
    }

    protected function getTableRecordUrlUsing(): Closure
    {
        return fn (Model $record) => false;
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [25, 50, 100];
    }
}
