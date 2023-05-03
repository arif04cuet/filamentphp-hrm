<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HolidayResource\Pages;
use App\Models\Holiday;
use App\Models\HolidayName;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Closure;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HolidayResource extends Resource
{
    protected static ?string $model = Holiday::class;

    protected static ?string $navigationIcon = 'heroicon-s-calendar';
    protected static ?string $navigationGroup = 'Holiday';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('type')->label('Type Of Holiday')->validationAttribute('Type Of Holiday')
                    ->options(Holiday::type())->searchable()->required(),
                Select::make('holiday_name_id')->label('Title')->validationAttribute('Title')
                    ->options(HolidayName::all()->pluck('title', 'id'))->searchable()
                    ->disabled(fn (Page $livewire) => $livewire instanceof EditRecord)->required(),
                DatePicker::make('date_from')->label('From')->weekStartsOnSunday()->reactive()
                    ->afterStateUpdated(function (Closure $get, $set) {
                        $set('date_to', $get('date_from'));
                        $count = \Carbon\Carbon::parse($get('date_from'))->diffInDays(\Carbon\Carbon::parse($get('date_to'))) + 1;
                        $set('count', $count);
                    })->required(),
                DatePicker::make('date_to')->label('To')->weekStartsOnSunday()->afterOrEqual('date_from')->reactive()
                    ->afterStateUpdated(function (Closure $get, $set) {
                        $count = \Carbon\Carbon::parse($get('date_from'))->diffInDays(\Carbon\Carbon::parse($get('date_to'))) + 1;
                        $set('count', $count);
                    }),
                TextInput::make('count')->label('Number Of Holidays')->disabled(),
                Select::make('flag')->label('Depend On Moon')
                    ->options([
                        'Yes' => 'Yes',
                        'No' => 'No'
                    ])->default('No')->disablePlaceholderSelection(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('#')->rowIndex()->size('sm'),
                TextColumn::make('type')->wrap()->sortable()->searchable()->size('sm'),
                TextColumn::make('holidayName.title')->label('Title')
                    ->formatStateUsing(fn ($record): string => $record->flag == 'Yes' ? '*' . $record->holidayName->title : $record->holidayName->title)->wrap()->sortable()->searchable()->size('sm'),
                TextColumn::make('from')->label('Date')->sortable()->searchable()->size('sm'),
            ])
            ->filters([
                Tables\Filters\Filter::make('flag')->label('Depens On Moon')
                    ->query(fn (Builder $query): Builder => $query->where('flag', 'Yes')),
                Tables\Filters\SelectFilter::make('type')->label('Holiday Type')
                    ->options(
                        Holiday::type(),
                    ),
                Tables\Filters\TrashedFilter::make(),
                // F.W : year filter here
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHolidays::route('/'),
            'create' => Pages\CreateHoliday::route('/create'),
            'edit' => Pages\EditHoliday::route('/{record}/edit'),
            'list-by-type' => Pages\ListHolidaysByType::route('/list-by-type'),
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
