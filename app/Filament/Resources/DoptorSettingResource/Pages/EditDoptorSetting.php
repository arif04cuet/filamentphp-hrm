<?php

namespace App\Filament\Resources\DoptorSettingResource\Pages;

use App\Filament\Resources\DoptorSettingResource;
use App\Models\DoptorSetting;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDoptorSetting extends EditRecord
{
    protected static string $resource = DoptorSettingResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
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
        foreach ($data['ips'] as $list) {
            $data['whitelisted_ips'][] = $list['ip'];
            $data['ips_active_inactive'][] = $list['status'];
        }
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}