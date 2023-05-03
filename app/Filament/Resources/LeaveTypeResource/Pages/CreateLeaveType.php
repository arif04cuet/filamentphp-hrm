<?php

namespace App\Filament\Resources\LeaveTypeResource\Pages;

use App\Filament\Resources\LeaveTypeResource;
use App\Models\LeaveType;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateLeaveType extends CreateRecord
{
    protected static string $resource = LeaveTypeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $exist = LeaveType::where('name', $data['name'])->first();
        if ($exist) {
            Notification::make()
                ->title('This leave type already exists')->warning()->send();
            $this->halt();
        } else {
            return $data;
        }
    }

    protected function getFormActions(): array
    {
        return array_merge(
            [$this->getCreateFormAction()->label('Save')->icon('heroicon-o-plus-circle')->size('sm')],
            static::canCreateAnother() ? [$this->getCreateAnotherFormAction()->label('Save & Add Another Leave Type')->icon('heroicon-o-plus-circle')->size('sm')] : [],
            [$this->getCancelFormAction()->label('Back')->icon('heroicon-o-arrow-left')->size('sm')],
        );
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
