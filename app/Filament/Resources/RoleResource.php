<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Models\Role;
use Closure;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Forms;
use Filament\Resources\Form;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Unique;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;

class RoleResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-s-cog';
    protected static ?string $navigationGroup = 'App Settings';

    public static function getModel(): string
    {
        return config('permission.models.role', Role::class);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Name')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(
                                        ignorable: fn (?Role $record): ?Role => $record,
                                        callback: function (Unique $rule, callable $get): Unique {
                                            $guardName = $get('guard_name') ?? config('auth.defaults.guard');

                                            return $rule
                                                ->where('guard_name', $guardName);
                                        }
                                    ),
                                Forms\Components\Select::make('guard_name')
                                    ->label('Guard Name')
                                    ->nullable()
                                    ->options(function (): array {
                                        $guards = array_keys(config('auth.guards', []));

                                        return array_combine($guards, $guards);
                                    })
                                    ->default(config('auth.defaults.guard')),
                                Forms\Components\Toggle::make('select_all')
                                    ->label('Select All')
                                    ->onIcon('heroicon-s-shield-check')
                                    ->offIcon('heroicon-s-shield-exclamation')
                                    ->reactive()
                                    ->afterStateUpdated(function (Closure $set, $state) {
                                        foreach (Role::getEntities() as $entity) {
                                            $set($entity, $state);

                                            foreach (Role::getPermissions() as $perm) {
                                                $set($entity . '_' . $perm, $state);
                                            }
                                        }
                                    })
                            ]),
                    ]),
                Forms\Components\Grid::make([
                    'sm' => 2,
                    'lg' => 3,
                    'xl' => 3,
                ])
                    ->schema(static::getEntitySchema())
                    ->columns([
                        'sm' => 2,
                        'lg' => 2,
                        'xl' => 3,
                    ])
            ]);
    }

    protected static function getEntitySchema()
    {
        return collect(Role::getEntities())->reduce(function (array $entities, string $entity) {
            $entities[] = Forms\Components\Card::make()
                ->schema([
                    Forms\Components\Toggle::make($entity)
                        ->label(__($entity))
                        ->onIcon('heroicon-s-lock-open')
                        ->offIcon('heroicon-s-lock-closed')
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set, Closure $get, bool $state) use ($entity) {
                            collect(Role::getPermissions())
                                ->each(function (string $permission) use ($set, $entity, $state) {
                                    $set($entity . '_' . $permission, $state);
                                });

                            if (!$state) {
                                $set('select_all', false);
                            }

                            static::freshSelectAll($get, $set);
                        }),
                    Forms\Components\Fieldset::make('Permissions')
                        ->label('Permissions')
                        ->extraAttributes(['class' => 'text-primary-600', 'style' => 'border-color:var(--primary)'])
                        ->columns([
                            'default' => 2,
                            // 'xl' => 2
                        ])
                        ->schema(static::getPermissionsSchema($entity))
                ])
                ->columns(2)
                ->columnSpan(1);
            return $entities;
        }, []);
    }

    protected static function getPermissionsSchema(string $entity)
    {
        return collect(Role::getPermissions())->reduce(function (array $permissions, string $permission) use ($entity) {
            $permissions[] = Forms\Components\Checkbox::make($entity . '_' . $permission)
                ->label(__($permission))
                ->extraAttributes(['class' => 'text-primary-600'])
                ->afterStateHydrated(function (Closure $set, Closure $get, ?Role $record) use ($entity, $permission) {
                    if (is_null($record)) return;

                    $existed = $record->checkPermissionTo($entity . '_' . $permission);
                    $existed_Module = $record->checkPermissionTo($entity);

                    if ($existed) {
                        $set($entity . '_' . $permission, $existed);
                    }

                    if ($existed_Module) {
                        $set($entity, true);
                    } else {
                        $set($entity, false);
                        $set('select_all', false);
                    }

                    static::freshSelectAll($get, $set);
                })
                ->reactive()
                ->afterStateUpdated(function (Closure $set, Closure $get, bool $state) use ($entity) {
                    $permissionStates = [];
                    foreach (Role::getPermissions() as $perm) {
                        $permissionStates[] = $get($entity . '_' . $perm);
                    }

                    if (in_array(false, $permissionStates, true) === false) {
                        $set($entity, true); // if all permissions true => turn toggle on
                    }

                    if (in_array(false, $permissionStates, true) === true) {
                        $set($entity, false); // if even one false => turn toggle off
                    }

                    if (!$state) {
                        $set($entity, false);
                        $set('select_all', false);
                    }

                    static::freshSelectAll($get, $set);
                });
            return $permissions;
        }, []);
    }

    protected static function freshSelectAll(Closure $get, Closure $set): void
    {
        $entityStates = collect(Role::getEntities())
            ->map(fn (string $entity): bool => (bool)$get($entity));

        if ($entityStates->containsStrict(false) === false) {
            $set('select_all', true); // if all toggles on => turn select_all on
        }

        if ($entityStates->containsStrict(false) === true) {
            $set('select_all', false); // if even one toggle off => turn select_all off
        }
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->size('sm'),
                TextColumn::make('name')->sortable()->size('sm'),
                ToggleColumn::make('is_system')->sortable(),
                TextColumn::make('doptor.name_bn')->label('Doptor')->wrap()->sortable()->size('sm'),
                TextColumn::make('permissions_count')
                    ->getStateUsing(function (Model $record): float {
                        return $record->permissions->count();
                    })->size('sm'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
