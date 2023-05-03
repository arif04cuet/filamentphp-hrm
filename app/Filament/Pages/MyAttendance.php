<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class MyAttendance extends Page
{
    protected static ?string $navigationIcon = 'heroicon-s-clock';

    protected static string $view = 'filament.resources.attendance-list-resource.pages.attendance-calendar';

    protected static ?string $navigationGroup = 'Attendance';
    protected static ?int $navigationSort = 3;
}
