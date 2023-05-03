<?php

namespace App\Filament\Widgets;

use Closure;
use App\Models\LeaveCause;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;

class LeaveBalances extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getTableQuery(): Builder
    {
        return LeaveCause::query();
    }

    protected function getTableColumns(): array
    {
        return [
            // TextColumn::make('#')->rowIndex()->size('sm'),
            TextColumn::make('leave_type')->formatStateUsing(fn (LeaveCause $record): string => $record->leave_type->name . '  (' . $record->leave_cause . ')')->wrap()->size('sm'),
            TextColumn::make('total_leaves')->formatStateUsing(fn (LeaveCause $record): string => $record->totalDays($record->id))->wrap()->size('sm'),
            TextColumn::make('used')->formatStateUsing(fn (LeaveCause $record): string => $record->usedDays($record->id, auth()->user()->employee ? auth()->user()->employee->id : 0))->wrap()->size('sm'),
            TextColumn::make('available')->formatStateUsing(fn (LeaveCause $record): string => $record->availableDays($record->id, auth()->user()->employee ? auth()->user()->employee->id : 0))->wrap()->size('sm'),
        ];
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [5, 10];
    }
}
