<?php

namespace App\Filament\Resources\MyLeaveApplicationResource\Pages;

use App\Filament\Resources\MyLeaveApplicationResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\LeaveApplication;
use App\Models\Employee;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class CreateMyLeaveApplication extends CreateRecord
{
    protected static string $resource = MyLeaveApplicationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['employee_id'] = auth()->user()->employee->id;
        $data['total_leave_days'] = \Carbon\Carbon::parse($data['leave_from'])->diffInDays(\Carbon\Carbon::parse($data['leave_to'])) + 1;
        return $data;
    }

    protected function getFormActions(): array
    {
        return array_merge(
            [$this->getCreateFormAction()->label('Save')->icon('heroicon-o-plus-circle')->size('sm')],
            [$this->getCancelFormAction()->label('Back')->icon('heroicon-o-arrow-left')->size('sm')],
        );
    }

    protected function afterCreate(): void
    {
        $applicant = Employee::find($this->record->employee_id);
        $employee = Employee::find($this->record->apply_to);
        if ($employee->user) {
            $recipient = User::find($employee->user->id);
            $recipient->notify(
                Notification::make()
                    ->title('New leave application')
                    ->success()
                    ->body('A leave application is applied by ' . $applicant->name_bn . ' (' . $applicant->designation->name_bn . ')')
                    ->actions([
                        Action::make('view')
                            ->url(fn () => route('filament.resources.others-leave-applications.edit', ['record' => $this->record->id]))
                            ->button(),
                    ])
                    ->toDatabase(),
            );
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
