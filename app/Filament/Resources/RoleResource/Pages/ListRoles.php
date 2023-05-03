<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use App\Jobs\Doptor\SyncRolesWithDashboard;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    protected function getActions(): array
    {
        return [
            Actions\Action::make('sync')
                ->label('Sync')
                ->action('syncRoles')->icon('heroicon-o-arrow-up')->size('sm'),
            Actions\CreateAction::make()->label('Add Role')->icon('heroicon-o-plus-circle')->size('sm'),
        ];
    }

    public function syncRoles(): void
    {
        SyncRolesWithDashboard::dispatch();
        Notification::make()
            ->title('Synced successfully')
            ->success()
            ->send();
    }
}
