<?php

namespace App\Filament\Resources\HolidayNameResource\Pages;

use App\Filament\Resources\HolidayNameResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHolidayNames extends ListRecords
{
    protected static string $resource = HolidayNameResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Add Holiday Name')->icon('heroicon-o-plus-circle')->size('sm'),
        ];
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [25, 50, 100];
    }
}
