<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Resources\Form;
use XliteDev\FilamentImpersonate\Tables\Actions\ImpersonateAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;


class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-s-cog';
    protected static ?string $navigationGroup = 'App Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('id')->disabled(),
                TextInput::make('name')->label('Name')->disabled(),
                TextInput::make('email')->label('Email')->disabled(),
                TextInput::make('username')->label('Username')->disabled(),
                TextInput::make('mobile')->label('Mobile')->disabled(),
                TextInput::make('cdap_id')->label('CDAP Id')->disabled(),
                TextInput::make('office_id')->label('Office Id')->disabled(),
                TextInput::make('designation_id')->label('Designation Id')->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('#')->rowIndex()->size('sm'),
                TextColumn::make('name')->label('Name')->wrap()->sortable()->searchable()->size('sm'),
                TextColumn::make('email')->label('Email')->sortable()->searchable()->size('sm'),
                TextColumn::make('username')->label('Username')->sortable()->searchable()->size('sm'),
                TextColumn::make('mobile')->label('Mobile')->searchable()->size('sm'),
                TextColumn::make('doptor.name_bn')->wrap()->sortable()->searchable()->size('sm'),
                TextColumn::make('cdap_id')->label('CDAP Id')->toggleable(isToggledHiddenByDefault: true)->sortable()->searchable()->size('sm'),
            ])
            ->filters([
                //
            ])
            ->actions([
                ImpersonateAction::make()->visible(fn (User $user): bool => auth()->user()->can('impersonate', $user)),
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
            'index' => Pages\ListUsers::route('/'),
        ];
    }    

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('office_id', auth()->user()->office_id);
    }
}
