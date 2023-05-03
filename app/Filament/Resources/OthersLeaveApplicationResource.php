<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OthersLeaveApplicationResource\Pages;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\OthersLeaveApplication;
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

class OthersLeaveApplicationResource extends Resource
{
    protected static ?string $model = OthersLeaveApplication::class;
    protected static ?string $navigationGroup = 'Leave';
    protected static ?int $navigationSort = 5;

    protected static ?string $navigationIcon = 'heroicon-s-clipboard';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Select::make('employee_id')->label('Applicant')->options(Employee::all()->pluck('name_bn', 'id'))->disabled(),
                        Select::make('leave_type_id')->label('Type Of Leave')->options(LeaveType::all()->pluck('name', 'id'))->disabled(),
                        Select::make('leave_cause_id')->label('Cause Of Leave')->disabled()
                            ->options(function (Closure $get) {
                                $id = $get('leave_type_id');
                                return OthersLeaveApplication::leaveCauseByType($id);
                            }),
                        Select::make('apply_to')->label('Applied To')->options(Employee::all()->pluck('name_bn', 'id'))->disabled(),
                    ])
                    ->columns(4),
                Card::make()
                    ->schema([
                        DatePicker::make('leave_from')->label('From')->disabled(),
                        DatePicker::make('leave_to')->label('To')->disabled(),
                        TextInput::make('total_leave_days')->label('Number Of Leave days')->disabled(),
                    ])
                    ->columns(3),
                TextInput::make('address_during_leave')->disabled(),
                FileUpload::make('attachment')->multiple()->enableDownload()->enableOpen()->directory('leave-attachments')->disabled()->placeholder('Attached Files'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('#')->getStateUsing(static function ($rowLoop): string {
                    $i = OthersLeaveApplication::where('apply_to', auth()->user()->employee ? auth()->user()->employee->id : 0)->count();
                    return (string) $i - $rowLoop->iteration + 1;
                })->size('sm'),
                TextColumn::make('employee.name_bn')->label('Applicant')
                    ->description(fn ($record) => $record->employee->designation ? '(' . $record->employee->designation->name_bn . ')' : '')->wrap()->sortable()->searchable()->size('sm'),
                // TextColumn::make('employee.designation.name_bn')->label('Designation')->toggleable(isToggledHiddenByDefault: true)->wrap()->sortable()->searchable()->size('sm'),
                TextColumn::make('created_at')->label('Applied On')->formatStateUsing(fn ($record) => \Carbon\Carbon::parse($record->created_at)->toDateString())->wrap()->searchable()->size('sm'),
                TextColumn::make('leaveType.name')->wrap()->sortable()->searchable()->size('sm'),
                TextColumn::make('leaveCause.leave_cause')->wrap()->sortable()->searchable()->size('sm'),
                TextColumn::make('leave_dates')->label('Leave Duration')->formatStateUsing(fn ($record) => $record->leave_from . ' to ' . $record->leave_to)
                    ->description(fn ($record) => ' (' . $record->total_leave_days . ' day)')->size('sm'),
                TextColumn::make('address_during_leave')->label('Address')->toggleable(isToggledHiddenByDefault: true)->wrap()->sortable()->searchable()->size('sm'),
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
            'index' => Pages\ListOthersLeaveApplications::route('/'),
            'edit' => Pages\EditOthersLeaveApplication::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('apply_to', auth()->user()->employee ? auth()->user()->employee->id : 0)
            ->orderBy('id', 'DESC');
    }
}
