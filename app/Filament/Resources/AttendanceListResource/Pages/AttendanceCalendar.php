<?php

namespace App\Filament\Resources\AttendanceListResource\Pages;

use App\Filament\Resources\AttendanceListResource;
use Filament\Resources\Pages\Page;

class AttendanceCalendar extends Page
{
    protected static string $resource = AttendanceListResource::class;

    protected static string $view = 'filament.resources.attendance-list-resource.pages.attendance-calendar';
}
