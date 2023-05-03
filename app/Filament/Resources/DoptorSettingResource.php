<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DoptorSettingResource\Pages;
use App\Models\DoptorSetting;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\DoptorSettingResource\RelationManagers;
use App\Models\OfficeTime;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Builder;

class DoptorSettingResource extends Resource
{
    protected static ?string $model = DoptorSetting::class;

    protected static ?string $navigationIcon = 'heroicon-s-clock';
    protected static ?string $navigationGroup = 'Attendance';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Weekend')
                    ->schema([
                        CheckboxList::make('weekend')->label('')
                            ->options([
                                'Fri' => 'Friday',
                                'Sat' => 'Saturday',
                                'Sun' => 'Sunday',
                                'Mon' => 'Monday',
                                'Tue' => 'Tuesday',
                                'Wed' => 'Wednesday',
                                'Thu' => 'Thursday',
                            ])->required()->columns(7),
                    ]),

                Section::make('IP')
                    ->schema([
                        Repeater::make('ips')->label('')
                            ->schema([
                                TextInput::make('ip')->label('')->required(),
                                Toggle::make('status')
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->default('on')
                            ])
                            ->columns(2)
                            ->createItemButtonLabel('+ Add More')
                            ->minItems(1)
                            ->cloneable(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('weekend')->wrap()->size('sm'),
                TextColumn::make('doptor_time')->formatStateUsing(fn (OfficeTime $times): string => $times->officeTime(auth()->user()->office_id))->wrap()->size('sm'),
                TextColumn::make('whitelisted_ips')->label('Whitelisted IPs')->wrap()->searchable()->size('sm'),
                    // ->description(fn ($record): string => json_encode($record->ips_active_inactive))->wrap()->size('sm'),
                // TextColumn::make('whitelisted_ips')->label('Whitelisted IPs')->sortable()->searchable()
                //     ->limit(40)->tooltip(function (TextColumn $column): ?string {
                //         $state = $column->getState();
                //         if (strlen($state) <= $column->getLimit()) {
                //             return null;
                //         }
                //         // Only render the tooltip if the column contents exceeds the length limit.
                //         return $state;
                //     })
                //     ->description(fn (DoptorSetting $record): string => json_encode($record->ips_active_inactive))->wrap()->size('sm'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                // Tables\Actions\ViewAction::make()->label('Details')->icon('heroicon-o-wifi'),
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

    public static function getRelations(): array
    {
        return [
            RelationManagers\OfficeTimesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDoptorSettings::route('/'),
            'create' => Pages\CreateDoptorSetting::route('/create'),
            'edit' => Pages\EditDoptorSetting::route('/{record}/edit'),
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
