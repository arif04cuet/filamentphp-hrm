<?php

namespace App\Filament\Resources\DoptorSettingResource\Pages;

use App\Filament\Resources\DoptorSettingResource;
use App\Models\DoptorSetting;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateDoptorSetting extends CreateRecord
{
    protected static string $resource = DoptorSettingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['doptor_id'] = auth()->user()->office_id;
        $exist = DoptorSetting::where('doptor_id', $data['doptor_id'])->first();
        if ($exist) {
            Notification::make()
                ->title('Setting for this doptor already exists')
                ->warning()
                ->send();
            $this->halt();
        } else {
            foreach ($data['ips'] as $list) {
                $data['whitelisted_ips'][] = $list['ip'];
                $data['ips_active_inactive'][] = $list['status'];
            }
            return $data;
        }
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
