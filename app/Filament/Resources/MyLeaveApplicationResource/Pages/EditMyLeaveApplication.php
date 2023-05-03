<?php

namespace App\Filament\Resources\MyLeaveApplicationResource\Pages;

use App\Filament\Resources\MyLeaveApplicationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\LeaveApplication;
use App\Models\Employee;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class EditMyLeaveApplication extends EditRecord
{
    protected static string $resource = MyLeaveApplicationResource::class;

    protected function getActions(): array
    {
        return [
            Actions\Action::make('cancel')->label('Cancel Application')->action('cancelApplication')->icon('heroicon-o-x-circle')->color('danger')->size('sm'),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()->label('Save')->icon('heroicon-o-check-circle')->size('sm'),
            $this->getCancelFormAction()->label('Back')->icon('heroicon-o-arrow-left')->size('sm'),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['total_leave_days'] = \Carbon\Carbon::parse($data['leave_from'])->diffInDays(\Carbon\Carbon::parse($data['leave_to'])) + 1;
        return $data;
    }

    protected function afterSave(): void
    {
        $applicant = Employee::find($this->record->employee_id);
        $des = $applicant->designation ? $applicant->designation->name_bn : '';
        $employee = Employee::find($this->record->apply_to);
        if ($employee->user) {
            $recipient = User::find($employee->user->id);
            $recipient->notify(
                Notification::make()
                    ->title('Leave application modified')
                    ->success()
                    ->body('Some changes has been made to a leave application by ' . $applicant->name_bn . ' (' . $des . ')')
                    ->actions([
                        Action::make('view')
                            ->url(fn () => route('filament.resources.others-leave-applications.edit', ['record' => $this->record->id]))
                            ->button(),
                    ])
                    ->toDatabase(),
            );
        }
    }

    public function cancelApplication()
    {
        LeaveApplication::where('id', $this->record->id)->update(['status' => LeaveApplication::D_CANCELED]);

        $applicant = Employee::find($this->record->employee_id);
        $des = $applicant->designation ? $applicant->designation->name_bn : '';
        $employee = Employee::find($this->record->apply_to);
        if ($employee->user) {
            $recipient = User::find($employee->user->id);
            $recipient->notify(
                Notification::make()
                    ->title('Leave application modified')
                    ->success()
                    ->body('A leave application by ' . $applicant->name_bn . ' (' . $des . ')' . ' has been canceled')
                    ->actions([
                        Action::make('view')
                            ->url(fn () => route('filament.resources.others-leave-applications.index'))
                            ->button(),
                    ])
                    ->toDatabase(),
            );
        }
        return redirect()->route('filament.resources.my-leave-applications.index');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
