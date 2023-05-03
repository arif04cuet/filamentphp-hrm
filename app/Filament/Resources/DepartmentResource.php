<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartmentResource\Pages;
use App\Models\Department;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static ?string $navigationIcon = 'heroicon-s-briefcase';
    protected static ?string $navigationGroup = 'Organogram';
    protected static ?int $navigationSort = 2;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->searchable()->size('sm'),
                TextColumn::make('name_bn')->label('Dept. Name')->wrap()->sortable()->searchable()->size('sm'),
                TextColumn::make('name_en')->label('Name En')->wrap()->sortable()->searchable()->size('sm'),
                TextColumn::make('department_code')->label('Dept. Code')->wrap()->sortable()->searchable()->size('sm'),
                TextColumn::make('doptor.name_bn')->wrap()->sortable()->searchable()->size('sm'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDepartments::route('/'),
        ];
    }
}
