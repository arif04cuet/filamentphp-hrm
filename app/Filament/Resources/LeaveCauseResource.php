<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveCauseResource\Pages;
use App\Models\LeaveCause;
use App\Models\LeaveType;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\ActionGroup;
use Closure;
use Filament\Forms;
use App\Filament\Resources\LeaveCauseResource\RelationManagers;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Builder;

class LeaveCauseResource extends Resource
{
    protected static ?string $model = LeaveCause::class;

    protected static ?string $navigationIcon = 'heroicon-s-clipboard';
    protected static ?string $navigationGroup = 'Leave';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('leave_type_id')->label('Type Of Leave')
                    ->options(LeaveType::all()->pluck('name', 'id'))
                    ->searchable()->required(),
                TextInput::make('leave_cause')
                    ->label('Leave Cause Name')->required(),
                Fieldset::make('Available Leave')
                    ->schema([
                        Select::make('per_date_type')->label('For Permanent Emp')
                            ->options(LeaveCause::day_type())
                            ->searchable()->required()->reactive(),
                        TextInput::make('per_leave_days')->label('Value')
                            ->hidden(fn (Closure $get) => $get('per_date_type') !== 'Day' && $get('per_date_type') !== 'Month' && $get('per_date_type') !== 'Year')
                            ->required(),
                        Select::make('temp_date_type')->label('For Temporary Emp')
                            ->options(LeaveCause::day_type())
                            ->searchable()->required()->reactive(),
                        TextInput::make('temp_leave_days')->label('Value')
                            ->hidden(fn (Closure $get) => $get('temp_date_type') !== 'Day' && $get('temp_date_type') !== 'Month' && $get('temp_date_type') !== 'Year')
                            ->required(),
                        Select::make('leave_status')
                            ->options(LeaveCause::leave_status())
                            ->searchable()->required(),
                    ])
                    ->columns(5),
                Repeater::make('validation_json')->label('Special Note(s)')
                    ->schema([
                        TextInput::make('note')->label('Write Note')
                    ])
                    ->defaultItems(0)->createItemButtonLabel('+ Add'),
                Repeater::make('attachment_json')->label('Required Attachment(s)')
                    ->schema([
                        TextInput::make('file')->label('Write Attachment Detail')
                    ])
                    ->defaultItems(0)->createItemButtonLabel('+ Add'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('#')->rowIndex()->size('sm'),
                TextColumn::make('leave_type.name')->wrap()->sortable()->searchable()->size('sm'),
                TextColumn::make('leave_cause')->wrap()->sortable()->searchable()->size('sm'),
                TextColumn::make('per_leave_days')->label('For Permanent')->formatStateUsing(fn (LeaveCause $record): string => $record->per_leave_days . ' ' . $record->per_date_type)
                    ->wrap()->sortable()->searchable()->size('sm'),
                TextColumn::make('temp_leave_days')->label('For Temporary')->formatStateUsing(fn (LeaveCause $record): string => $record->temp_leave_days . ' ' . $record->temp_date_type)
                    ->wrap()->sortable()->searchable()->size('sm'),
                TextColumn::make('validation')->label('Note(s)')->wrap()->sortable()->searchable()->size('sm'),
                TextColumn::make('attachment')->label('Attachment(s)')->wrap()->sortable()->searchable()->size('sm'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('leave_type_id')->label('Leave Type')
                    ->options(
                        LeaveType::all()->pluck('name', 'id'),
                    ),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]);
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
            'index' => Pages\ListLeaveCauses::route('/'),
            'create' => Pages\CreateLeaveCause::route('/create'),
            'edit' => Pages\EditLeaveCause::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
