<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DesignationResource\Pages;
use App\Models\Designation;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;

class DesignationResource extends Resource
{
    protected static ?string $model = Designation::class;

    protected static ?string $navigationIcon = 'heroicon-s-briefcase';
    protected static ?string $navigationGroup = 'Organogram';
    protected static ?int $navigationSort = 3;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->searchable()->size('sm'),
                TextColumn::make('name_bn')->label('Designation Name')->wrap()->sortable()->searchable()->size('sm'),
                TextColumn::make('name_en')->label('Name En')->wrap()->sortable()->searchable()->size('sm'),
                TextColumn::make('employee.name_bn')->wrap()->sortable()->searchable()->size('sm'),
                TextColumn::make('department.name_bn')->wrap()->sortable()->searchable()->size('sm'),
                TextColumn::make('doptor.name_bn')->wrap()->sortable()->searchable()->size('sm'),
            ])
            ->filters([
                Filter::make('employee_id')->label('Employee Assigned')
                    ->query(fn (Builder $query): Builder => $query->whereNot('employee_id', null))
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDesignations::route('/'),
        ];
    }
}
