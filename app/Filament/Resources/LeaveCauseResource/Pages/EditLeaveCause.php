<?php

namespace App\Filament\Resources\LeaveCauseResource\Pages;

use App\Filament\Resources\LeaveCauseResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeaveCause extends EditRecord
{
    protected static string $resource = LeaveCauseResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make()->icon('heroicon-o-trash')->size('sm'),
        ];
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
        if ($data['per_date_type'] != 'Day' && $data['per_date_type'] != 'Month' && $data['per_date_type'] != 'Year')
            $data['per_leave_days'] = null;

        if ($data['temp_date_type'] != 'Day' && $data['temp_date_type'] != 'Month' && $data['temp_date_type'] != 'Year')
            $data['temp_leave_days'] = null;

        $data['validation'] = null;
        $data['attachment'] = null;
        
        foreach ($data['validation_json'] as $list) {
            if ($list['note'] != '')
                $data['validation'][] = $list['note'];
            else
                $data['validation'][] = '';
        }

        foreach ($data['attachment_json'] as $list) {
            if ($list['file'] != '')
                $data['attachment'][] = $list['file'];
            else
                $data['attachment'][] = '';
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
