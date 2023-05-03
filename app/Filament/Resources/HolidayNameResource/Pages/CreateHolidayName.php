<?php

namespace App\Filament\Resources\HolidayNameResource\Pages;

use App\Filament\Resources\HolidayNameResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateHolidayName extends CreateRecord
{
    protected static string $resource = HolidayNameResource::class;

    protected function getFormActions(): array
    {
        return array_merge(
            [$this->getCreateFormAction()->label('Save')->icon('heroicon-o-plus-circle')->size('sm')],
            static::canCreateAnother() ? [$this->getCreateAnotherFormAction()->label('Save & Add Another Holiday Name')->icon('heroicon-o-plus-circle')->size('sm')] : [],
            [$this->getCancelFormAction()->label('Back')->icon('heroicon-o-arrow-left')->size('sm')],
        );
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}