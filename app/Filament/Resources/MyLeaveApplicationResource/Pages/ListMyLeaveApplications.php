<?php

namespace App\Filament\Resources\MyLeaveApplicationResource\Pages;

use App\Filament\Resources\MyLeaveApplicationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Closure;
use Illuminate\Database\Eloquent\Model;

class ListMyLeaveApplications extends ListRecords
{
    protected static string $resource = MyLeaveApplicationResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->label('New Application')->icon('heroicon-o-plus-circle')->size('sm'),
        ];
    }

    protected function getTableRecordUrlUsing(): Closure
    {
        return fn (Model $record) => false;
    }
}
