<?php

namespace App\Filament\Resources\HolidayResource\Pages;

use App\Filament\Resources\HolidayResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHolidays extends ListRecords
{
    protected static string $resource = HolidayResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Add Holiday')->icon('heroicon-o-plus-circle')->size('sm'),
            Actions\Action::make('view')->label('Categorized List')->url('/admin/holidays/list-by-type')->icon('heroicon-o-stop')->size('sm'),
        ];
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [25, 50, 100];
    }
}