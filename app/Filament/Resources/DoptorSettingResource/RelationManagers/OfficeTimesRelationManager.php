<?php

namespace App\Filament\Resources\DoptorSettingResource\RelationManagers;

use App\Models\OfficeTime;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use HusamTariq\FilamentTimePicker\Forms\Components\TimePickerField;

class OfficeTimesRelationManager extends RelationManager
{
    protected static string $relationship = 'officeTimes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Time Setting')
                    ->schema([
                        DatePicker::make('from_date')->weekStartsOnSunday()->required(),
                        DatePicker::make('to_date')->weekStartsOnSunday()->after('from_date')->hint('(Optional)'),
                        // TimePicker::make('from_time')->withoutSeconds()->hint('24h')->hintIcon('heroicon-s-clock')->required(),
                        // TimePicker::make('to_time')->after('from_time')->withoutSeconds()->hint('24h')->hintIcon('heroicon-s-clock')->required(),
                        // Toggle::make('status')->onColor('success')->offColor('danger')->default('on')->inline(false)->required(),
                        TimePickerField::make('from_time')->okLabel("Confirm")->cancelLabel("Cancel")->required(),
                        TimePickerField::make('to_time')->okLabel("Confirm")->cancelLabel("Cancel")->after('from_time')->required(),
                        Hidden::make('id'),
                    ])->columns(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('Date')->formatStateUsing(fn (OfficeTime $record): string => $record->from_date . ' to ' . $record->to_date)->size('sm'),
                TextColumn::make('Time')->formatStateUsing(fn (OfficeTime $record): string => \Carbon\Carbon::parse($record->from_time)
                    ->format('g:i A') . ' to ' . \Carbon\Carbon::parse($record->to_time)->format('g:i A'))->size('sm'),
                ToggleColumn::make('status')->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make()->label('Add Office Time')->icon('heroicon-o-plus-circle')
                    ->mutateFormDataUsing(function (array $data, CreateAction $action) {
                        $sameDate = OfficeTime::where('doptor_id', auth()->user()->office_id)->where('from_date', $data['from_date'])->where('to_date', $data['to_date'])->first();
                        if ($sameDate) {
                            Notification::make()
                                ->title('Office Time is already added for this dates')->warning()->send();
                            $action->halt();
                        } else {
                            $allPrev = OfficeTime::where('doptor_id', auth()->user()->office_id)->get();
                            foreach ($allPrev as $prev) {
                                OfficeTime::where('id', $prev->id)->update(['status' => 0]);
                            }
                            $data['status'] = 1;
                            return $data;
                        }
                    })->after(function () {
                        return redirect('/admin/doptor-settings');
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(function (array $data, EditAction $action) {
                        $sameDate = OfficeTime::where('doptor_id', auth()->user()->office_id)->where('id', '!=', $data['id'])->where('from_date', $data['from_date'])->where('to_date', $data['to_date'])->first();
                        if ($sameDate) {
                            Notification::make()->title('Office Time is already added for this dates')->warning()->send();
                            $action->halt();
                        } else
                            return $data;
                    }),
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

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
