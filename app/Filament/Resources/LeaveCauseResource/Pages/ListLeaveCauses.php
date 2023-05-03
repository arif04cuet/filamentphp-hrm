<?php

namespace App\Filament\Resources\LeaveCauseResource\Pages;

use App\Filament\Resources\LeaveCauseResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeaveCauses extends ListRecords
{
    protected static string $resource = LeaveCauseResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Add Leave Cause')->icon('heroicon-o-plus-circle')->size('sm'),
        ];
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [25, 50, 100];
    }
}
