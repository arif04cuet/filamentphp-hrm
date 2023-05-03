<?php

namespace App\Filament\Resources\DoptorSettingResource\Pages;

use App\Filament\Resources\DoptorSettingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\DoptorSetting;

class ListDoptorSettings extends ListRecords
{
    protected static string $resource = DoptorSettingResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Add Doptor Setting')->icon('heroicon-o-plus-circle')->size('sm')
                ->visible(fn (): bool => DoptorSetting::where('doptor_id', auth()->user()->office_id)->first() ? false : true),
        ];
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [25, 50, 100];
    }
}
