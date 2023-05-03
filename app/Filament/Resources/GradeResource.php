<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GradeResource\Pages;
use App\Filament\Resources\GradeResource\RelationManagers;
use App\Models\Grade;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GradeResource extends Resource
{
    protected static ?string $model = Grade::class;
    protected static ?string $navigationIcon = 'heroicon-s-briefcase';
    protected static ?string $navigationGroup = 'Organogram';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // TextColumn::make('#')->rowIndex()->size('sm'),
                TextColumn::make('class_name')->wrap()->sortable()->searchable()->size('sm'),
                TextColumn::make('grade_name')->wrap()->sortable()->searchable()->size('sm'),
                TextColumn::make('from')->label('Payscale Range')->formatStateUsing(fn ($record): string => $record->from . ' - ' . $record->to . ' Taka')->size('sm')->sortable()->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('class_name')
                    ->options(
                        Grade::all()->pluck('class_name', 'class_name'),
                    ),
                Tables\Filters\SelectFilter::make('grade_name')
                    ->options(
                        Grade::all()->pluck('grade_name', 'grade_name'),
                    ),
            ])
            ->actions([])->defaultSort('class_name');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGrades::route('/'),
        ];
    }
}
