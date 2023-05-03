<?php

namespace App\Providers;

use App\Filament\Resources\AttendanceListResource;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\UserMenuItem;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Filament::serving(function () {
            Filament::registerNavigationGroups([
                NavigationGroup::make()
                    ->label('Organogram')
                    ->icon('heroicon-o-briefcase')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Attendance')
                    ->icon('heroicon-o-clock')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Leave')
                    ->icon('heroicon-o-clipboard')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Holiday')
                    ->icon('heroicon-o-calendar')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('App Settings')
                    ->icon('heroicon-o-cog')
                    ->collapsed(),
            ]);
            Filament::registerNavigationItems([]);
            Filament::registerUserMenuItems([
                // 'account' => UserMenuItem::make()->url(route('filament.pages.account')),
                // 'logout' => UserMenuItem::make()->label('Log out')->url(route('test')),
            ]);
        });

        Paginator::defaultView('layouts.partials.pagination');
    }
}
