<?php

namespace App\Filament\Resources\OthersLeaveApplicationResource\Pages;

use App\Filament\Resources\OthersLeaveApplicationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\LeaveApplication;
use App\Models\Employee;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class EditOthersLeaveApplication extends EditRecord
{
    protected static string $resource = OthersLeaveApplicationResource::class;

    protected function getActions(): array
    {
        return [
            Actions\Action::make('approve')->label('Approve Application')->action('approve')->color('success')->icon('heroicon-o-check-circle')->size('sm'),
            // ->url(fn () => route('approve-application', ['id' => $this->record->id])),
            Actions\Action::make('reject')->label('Reject Application')->action('reject')->color('danger')->icon('heroicon-o-x')->size('sm'),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCancelFormAction()->label('Back')->icon('heroicon-o-arrow-left')->size('sm'),
        ];
    }

    public function approve()
    {
        LeaveApplication::where('id', $this->record->id)->update([
            'status' => LeaveApplication::B_APPROVED,
            'approved_by' => auth()->user()->employee->id
        ]);

        $employee = Employee::find($this->record->employee_id);
        $des = auth()->user()->employee->designation ? auth()->user()->employee->designation->name_bn : '';
        if ($employee->user) {
            $recipient = User::find($employee->user->id);
            $recipient->notify(
                Notification::make()
                    ->title('Leave application accepted')
                    ->success()
                    ->body('Your leave application has been approved by ' . auth()->user()->employee->name_bn . ' (' . $des . ')')
                    ->actions([
                        Action::make('view')
                            ->url(fn () => route('filament.resources.my-leave-applications.index'))
                            ->button(),
                    ])
                    ->toDatabase(),
            );
        }
        return redirect()->route('filament.resources.others-leave-applications.index');
    }

    public function reject()
    {
        LeaveApplication::where('id', $this->record->id)->update(['status' => LeaveApplication::C_REJECTED]);

        $employee = Employee::find($this->record->employee_id);
        $des = auth()->user()->employee->designation ? auth()->user()->employee->designation->name_bn : '';
        if ($employee->user) {
            $recipient = User::find($employee->user->id);
            $recipient->notify(
                Notification::make()
                    ->title('Leave application rejected')
                    ->danger()
                    ->body('Your leave application has been rejected by ' . auth()->user()->employee->name_bn . ' (' . $des . ')')
                    ->actions([
                        Action::make('view')
                            ->url(fn () => route('filament.resources.my-leave-applications.index'))
                            ->button(),
                    ])
                    ->toDatabase(),
            );
        }
        return redirect()->route('filament.resources.others-leave-applications.index');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
