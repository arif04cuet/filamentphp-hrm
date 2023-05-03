<?php

namespace App\Filament\Resources\HolidayResource\Pages;

use App\Filament\Resources\HolidayResource;
use App\Models\Holiday;
use Filament\Resources\Pages\Page;

class ListHolidaysByType extends Page
{
    protected static string $resource = HolidayResource::class;

    protected static string $view = 'filament.resources.holiday-resource.pages.list-holidays-by-type';
}