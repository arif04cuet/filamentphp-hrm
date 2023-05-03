<?php

namespace App\Filament\Resources\HolidayResource\Pages;

use App\Filament\Resources\HolidayResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Holiday;
use Filament\Notifications\Notification;

class EditHoliday extends EditRecord
{
    protected static string $resource = HolidayResource::class;

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()->label('Save')->icon('heroicon-o-check-circle')->size('sm'),
            $this->getCancelFormAction()->label('Back')->icon('heroicon-o-arrow-left')->size('sm'),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['from'] = \Carbon\Carbon::parse($data['date_from'])->format('l, j F Y');
        $data['to'] = \Carbon\Carbon::parse($data['date_to'])->format('l, j F Y');
        $same = Holiday::where('id', '!=', $this->record->id)->where('holiday_name_id', $data['holiday_name_id'])->where('date_from', $data['date_from'])->first();
        if ($same) {
            if ($same->type == $data['type'] || $same->to == $data['date_to'] || $same->flag == $data['flag']) {
                Notification::make()
                    ->title('This Holiday is already added for this year')
                    ->warning()
                    ->send();
                $this->halt();
            } else
                return $data;
        } else
            return $data;
    }

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make()->icon('heroicon-o-trash')->size('sm'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
