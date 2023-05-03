<?php

namespace App\Filament\Resources\LeaveTypeResource\Pages;

use App\Filament\Resources\LeaveTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\LeaveType;
use Filament\Notifications\Notification;

class EditLeaveType extends EditRecord
{
    protected static string $resource = LeaveTypeResource::class;

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

    protected function mutateFormDataBeforeSave(array $data): array
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

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make()->icon('heroicon-o-trash')->size('sm'),
        ];
    }
}
