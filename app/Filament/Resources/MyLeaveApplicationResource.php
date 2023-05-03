<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MyLeaveApplicationResource\Pages;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\MyLeaveApplication;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Closure;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class MyLeaveApplicationResource extends Resource
{
    protected static ?string $model = MyLeaveApplication::class;
    protected static ?string $navigationGroup = 'Leave';
    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-s-clipboard';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Select::make('leave_type_id')->label('Type Of Leave')->searchable()
                            ->options(LeaveType::all()->pluck('name', 'id'))->reactive()->required(),
                        Select::make('leave_cause_id')->label('Cause Of Leave')->reactive()->required()
                            ->options(function (Closure $get) {
                                $id = $get('leave_type_id');
                                return MyLeaveApplication::leaveCauseByType($id);
                            }),
                        Select::make('apply_to')->options(Employee::optionsNameDes())->searchable()->required(),
                    ])
                    ->columns(3),
                Card::make()
                    ->schema([
                        DatePicker::make('leave_from')->label('From')->validationAttribute('Start Date')
                            ->weekStartsOnSunday()->reactive()->afterStateUpdated(function (Closure $get, $set) {
                                $total_leave_days = \Carbon\Carbon::parse($get('leave_from'))->diffInDays(\Carbon\Carbon::parse($get('leave_to'))) + 1;
                                $set('total_leave_days', $total_leave_days);
                            })->required(),
                        DatePicker::make('leave_to')->label('To')->validationAttribute('End Date')->afterOrEqual('leave_from')
                            ->weekStartsOnSunday()->reactive()->afterStateUpdated(function (Closure $get, $set) {
                                $total_leave_days = \Carbon\Carbon::parse($get('leave_from'))->diffInDays(\Carbon\Carbon::parse($get('leave_to'))) + 1;
                                $set('total_leave_days', $total_leave_days);
                            })->required(),
                        TextInput::make('total_leave_days')->label('Number Of Leave days')->disabled(),
                    ])
                    ->columns(3),
                TextInput::make('address_during_leave')->required(),
                FileUpload::make('attachment')->multiple()->enableDownload()->enableOpen()->directory('leave-attachments'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('#')->getStateUsing(static function ($rowLoop): string {
                    $i = MyLeaveApplication::where('employee_id', auth()->user()->employee ? auth()->user()->employee->id : 0)->count();
                    return (string) $i - $rowLoop->iteration + 1;
                })->size('sm'),
                TextColumn::make('created_at')->label('Applied On')->formatStateUsing(fn ($record) => \Carbon\Carbon::parse($record->created_at)->toDateString())->wrap()->searchable()->size('sm'),
                TextColumn::make('leaveType.name')->wrap()->sortable()->searchable()->size('sm'),
                TextColumn::make('leaveCause.leave_cause')->wrap()->sortable()->searchable()->size('sm'),
                TextColumn::make('leave_dates')->label('Leave Duration')->formatStateUsing(fn ($record) => $record->leave_from . ' to ' . $record->leave_to)
                    ->description(fn ($record) => ' (' . $record->total_leave_days . ' day)')->size('sm'),
                TextColumn::make('address_during_leave')->label('Address')->toggleable(isToggledHiddenByDefault: true)->wrap()->sortable()->searchable()->size('sm'),
                TextColumn::make('applyTo.name_bn')->label('Applied To')->wrap()->sortable()->searchable()->size('sm'),
                TextColumn::make('status')->wrap()->sortable()->searchable()->size('sm'),
                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'Pending',
                        'success' => 'Approved',
                        'danger' => 'Rejected',
                    ])->size('sm')
            ])
            ->filters([
                Tables\Filters\Filter::make('pending')->label('Pending Applications')->query(fn (Builder $query): Builder => $query->where('status', 'Pending')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->visible(fn ($record): bool => isset($record) ? $record->status == 'Pending' : false),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMyLeaveApplications::route('/'),
            'create' => Pages\CreateMyLeaveApplication::route('/create'),
            'edit' => Pages\EditMyLeaveApplication::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('employee_id', auth()->user()->employee ? auth()->user()->employee->id : 0)
            ->orderBy('id', 'DESC');
    }
}
