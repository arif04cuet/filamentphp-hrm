<?php

namespace App\Filament\Resources\LeaveCauseResource\Pages;

use App\Filament\Resources\LeaveCauseResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLeaveCause extends CreateRecord
{
    protected static string $resource = LeaveCauseResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
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

    protected function getFormActions(): array
    {
        return array_merge(
            [$this->getCreateFormAction()->label('Save')->icon('heroicon-o-plus-circle')->size('sm')],
            [$this->getCancelFormAction()->label('Back')->icon('heroicon-o-arrow-left')->size('sm')],
        );
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
