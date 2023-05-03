<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceListResource\Pages;
use App\Models\AttendanceList;
use App\Models\Employee;
use Filament\Resources\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Grid;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Hidden;
use Closure;
use Filament\Tables\Columns\BadgeColumn;

class AttendanceListResource extends Resource
{
    protected static ?string $model = AttendanceList::class;

    protected static ?string $navigationIcon = 'heroicon-s-clock';
    protected static ?string $navigationGroup = 'Attendance';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('employee')->disabled(),
                TextInput::make('doptor')->disabled(),
                Grid::make(5)
                    ->schema([
                        TextInput::make('date')->disabled(),
                        TextInput::make('ip')->disabled(),
                        TextInput::make('entry_time')->disabled(),
                        TextInput::make('status')->disabled(),
                        DatePicker::make('leave_time')->disabled(),
                    ]),
                TextInput::make('late_cause')->hidden(fn (Closure $get) => $get('status') !== 'LATE')->disabled(),
                DatePicker::make('approved_at')->hidden(fn (Closure $get) => $get('status') !== 'LATE')->disabled(),
                Hidden::make('id'),
                Hidden::make('late_flag'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('#')->getStateUsing(static function ($rowLoop): string {
                    $i = AttendanceList::where('employee_id', '!=', auth()->user()->employee->id)->count();
                    return (string) $i - $rowLoop->iteration + 1;
                })->size('sm'),
                TextColumn::make('employee.name_bn')->description(fn ($record) => $record->employee->designation ? '(' . $record->employee->designation->name_bn . ')' : '')
                    ->wrap()->sortable()->searchable()->size('sm'),
                TextColumn::make('date')->searchable()->size('sm'),
                TextColumn::make('in_out')->label('In | Out')->formatStateUsing(fn ($record) => $record->leave_time ? $record->entry_time . ' | ' . $record->leave_time : $record->entry_time . ' | -')->size('sm'),
                BadgeColumn::make('status')
                    ->colors([
                        'success' => 'PRESENT',
                        'danger' => 'LATE',
                    ])->searchable()->size('sm'),
                TextColumn::make('late_cause')->wrap()->searchable()->size('sm'),
                BadgeColumn::make('approved?')->formatStateUsing(fn ($record) => $record->approve($record->id))->colors(['danger' => 'No'])->size('sm'),
            ])
            ->filters([
                Tables\Filters\Filter::make('late_flag')->label('Late')
                    ->query(fn (Builder $query): Builder => $query->where('late_flag', 1)),
                Tables\Filters\Filter::make('unapproved')->label('Unapproved')
                    ->query(fn (Builder $query): Builder => $query->where('late_flag', 1)->where('approved_at', null)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->mutateRecordDataUsing(function (array $data, $record): array {
                        $employee = Employee::find($record->employee_id);
                        $data['doptor'] = $employee->doptor->name_bn;
                        if ($employee->designation)
                            $data['employee'] = $employee->name_bn . ' (' . $employee->designation->name_bn . ')';
                        else
                            $data['employee'] = $employee->name_bn;
                        return $data;
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendanceLists::route('/'),
            'edit' => Pages\EditAttendanceList::route('/{record}/edit'),
            // 'attendance-calendar' => Pages\AttendanceCalendar::route('/calendar'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('employee_id', '!=', auth()->user()->employee ? auth()->user()->employee->id : 0)->orderBy('id', 'DESC');
    }
}
