<?php

namespace App\Filament\Resources\HolidayNameResource\Pages;

use App\Filament\Resources\HolidayNameResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHolidayName extends EditRecord
{
    protected static string $resource = HolidayNameResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()->label('Save')->icon('heroicon-o-check-circle')->size('sm'),
            $this->getCancelFormAction()->label('Back')->icon('heroicon-o-arrow-left')->size('sm'),
        ];
    }

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make()->icon('heroicon-o-trash')->size('sm'),
        ];
    }
}