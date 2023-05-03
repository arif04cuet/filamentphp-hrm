<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use App\Models\Permission;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Arr;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    public array $permissions;

    public function beforeCreate(): void
    {
        $this->permissions = array_keys(array_filter(Arr::except($this->data, ['name', 'select_all', 'guard_name'])));
    }

    public function afterCreate(): void
    {
        $permissions = [];
        foreach ($this->permissions as $name) {
            $permissions[] = Permission::findOrCreate($name, $this->record->guard_name);
        }
        $this->record->syncPermissions($permissions);

        if ($employee = auth()->user()->employee)
            $this->record->update(['doptor_id' => $employee->doptor->id]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return Arr::only($this->data, ['name', 'guard_name']);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function getFormActions(): array
    {
        return array_merge(
            [$this->getCreateFormAction()->label('Save')->icon('heroicon-o-plus-circle')->size('sm')],
            static::canCreateAnother() ? [$this->getCreateAnotherFormAction()->label('Save & Add Another Role')->icon('heroicon-o-plus-circle')->size('sm')] : [],
            [$this->getCancelFormAction()->label('Back')->icon('heroicon-o-arrow-left')->size('sm')],
        );
    }

}
