<?php

namespace App\Filament\Resources\DoptorResource\Pages;

use App\Filament\Resources\DoptorResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDoptors extends ListRecords
{
    protected static string $resource = DoptorResource::class;

    protected function getActions(): array
    {
        return [
            Actions\Action::make('view')
                ->label('On Board')
                ->url('/admin/doptors/office-on-board')
                ->size('sm'),
        ];
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [25, 50, 100];
    }
}