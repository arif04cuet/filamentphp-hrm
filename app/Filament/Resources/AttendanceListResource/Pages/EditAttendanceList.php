<?php

namespace App\Filament\Resources\AttendanceListResource\Pages;

use App\Filament\Resources\AttendanceListResource;
use App\Models\AttendanceList;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Employee;

class EditAttendanceList extends EditRecord
{
    protected static string $resource = AttendanceListResource::class;

    protected function getActions(): array
    {
        return [
            Actions\Action::make('approve')->action('approve')->color('success')->icon('heroicon-o-check-circle')->size('sm')
                ->visible(fn (): bool => isset($this->record->status) && ($this->record->approved_at == null) ? $this->record->status == 'LATE' : false),
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
        AttendanceList::where('id', $this->record->id)->update([
            'approved_at' => \Carbon\Carbon::now()
        ]);
        return redirect()->route('filament.resources.attendance-lists.index');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $employee = Employee::find($this->record->employee_id);
        $data['doptor'] = $employee->doptor->name_bn;
        if ($employee->designation)
            $data['employee'] = $employee->name_bn . ' (' . $employee->designation->name_bn . ')';
        else
            $data['employee'] = $employee->name_bn;
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
