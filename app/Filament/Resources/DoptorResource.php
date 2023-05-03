<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DoptorResource\Pages;
use App\Models\Doptor;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

class DoptorResource extends Resource
{
    protected static ?string $model = Doptor::class;

    protected static ?string $navigationIcon = 'heroicon-s-briefcase';
    protected static ?string $navigationGroup = 'Organogram';
    protected static ?int $navigationSort = 1;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->searchable()->size('sm'),
                TextColumn::make('name_bn')->label('Doptor Name')->wrap()->sortable()->searchable()->size('sm'),
                TextColumn::make('name_en')->label('Name En')->wrap()->sortable()->searchable()->size('sm'),
            ])
            ->actions([
                Action::make('Sync')->icon('heroicon-o-arrow-up')->url(fn ($record) => "settings/office/sync/$record->id")->successNotificationTitle('Synced')
                ->visible(fn ($record): bool => auth()->user()->office_id == $record->id),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDoptors::route('/'),
            'office-on-board' => Pages\OfficeOnBoard::route('/office-on-board'),
        ];
    }    

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('id', auth()->user()->office_id);
    }
}