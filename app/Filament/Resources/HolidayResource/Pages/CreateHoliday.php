<?php

namespace App\Filament\Resources\HolidayResource\Pages;

use App\Filament\Resources\HolidayResource;
use App\Models\Holiday;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;

class CreateHoliday extends CreateRecord
{
    protected static string $resource = HolidayResource::class;

    protected function getFormActions(): array
    {
        return array_merge(
            [$this->getCreateFormAction()->label('Save')->icon('heroicon-o-plus-circle')->size('sm')],
            [$this->getCancelFormAction()->label('Back')->icon('heroicon-o-arrow-left')->size('sm')],
        );
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['from'] = \Carbon\Carbon::parse($data['date_from'])->format('l, j F Y');
        $data['to'] = \Carbon\Carbon::parse($data['date_to'])->format('l, j F Y');

        $same = Holiday::where('holiday_name_id', $data['holiday_name_id'])->where('date_from', $data['date_from'])->first();
        if ($same) {
            Notification::make()
                ->title('This Holiday is already added for this year')
                ->warning()
                ->send();
            $this->halt();
        } else
            return $data;
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Holiday !!')
            ->body('Holiday added successfully.');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
