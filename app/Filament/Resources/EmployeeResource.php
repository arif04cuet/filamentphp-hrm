<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Models\Employee;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Resources\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Toggle;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-s-briefcase';
    protected static ?string $navigationGroup = 'Organogram';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Tabs::make('Heading')
                            ->tabs([
                                Tabs\Tab::make('General Information')
                                    ->schema([
                                        TextInput::make('id')->label('Employee Id')->disabled(),
                                        TextInput::make('gov_id')->label('Govt Id')->disabled(),
                                        TextInput::make('name_bn')->label('Name')->disabled(),
                                        TextInput::make('name_en')->label('Name En')->disabled(),
                                        TextInput::make('email')->label('Email')->disabled(),
                                        TextInput::make('mobile_office')->label('Mobile')->disabled(),
                                        Toggle::make('is_permanent')->inline(false)->onColor('success')->offColor('danger')->disabled(),
                                    ]),
                                Tabs\Tab::make('Organogram')
                                    ->schema([
                                        TextInput::make('doptor')->disabled(),
                                        TextInput::make('department')->disabled(),
                                        TextInput::make('designation')->disabled(),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->wrap()->sortable()->searchable()->size('sm'),
                TextColumn::make('name_bn')->label('Emp Name')->wrap()->sortable()->searchable()->size('sm'),
                TextColumn::make('name_en')->label('Name En')->wrap()->sortable()->searchable()->size('sm'),
                TextColumn::make('gov_id')->label('Username')->wrap()->sortable()->searchable()->size('sm'),
                TextColumn::make('email')->wrap()->sortable()->searchable()->size('sm'),
                TextColumn::make('mobile_office')->label('Mobile')->toggleable(isToggledHiddenByDefault: true)->sortable()->searchable()->size('sm'),
                TextColumn::make('designation.name_bn')->wrap()->sortable()->searchable()->size('sm'),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make()->label('Details')->size('sm'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'view' => Pages\ViewEmployees::route('/view/{record}'),
        ];
    }
}
