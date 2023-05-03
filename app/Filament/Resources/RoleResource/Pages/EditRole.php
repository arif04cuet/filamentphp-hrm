<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use App\Models\Permission;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Arr;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;
    public array $permissions;

    protected function getActions(): array
    {
        return [];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->permissions = static::onlyPermissionsKeys($data);

        return Arr::only($data, ['name', 'guard_name']);
    }

    public function afterSave(): void
    {
        $permissions = [];
        foreach ($this->permissions as $name) {
            $permissions[] = Permission::firstOrCreate(
                ['name' => $name],
                ['guard_name' => $this->record->guard_name]
            );
        }

        $this->record->touch();
        $this->record->syncPermissions($permissions);
    }

    public static function onlyPermissionsKeys($data): array
    {
        return array_keys(array_filter(Arr::except($data, ['guard_name', 'id', 'name', 'select_all', 'created_at', 'updated_at'])));
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()->label('Save')->icon('heroicon-o-check-circle')->size('sm'),
            $this->getCancelFormAction()->label('Back')->icon('heroicon-o-arrow-left')->size('sm'),
        ];
    }
}
