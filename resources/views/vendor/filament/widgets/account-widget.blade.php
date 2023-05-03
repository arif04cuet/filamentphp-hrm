<x-filament::widget class="filament-account-widget col-span-2">
    <x-filament::card>
        @php
        $user = \Filament\Facades\Filament::auth()->user();
        $doptor = auth()->user()->doptor ? auth()->user()->doptor->name_bn : '';
        $designation = auth()->user()->employee && auth()->user()->employee->designation ? auth()->user()->employee->designation->name_bn ?? '' : '';
        @endphp

        <div class="flex items-center rtl:space-x-reverse text-center justify-center">
            <div>
                <h5 class="font-bold">{{auth()->user()->name}}</h5>
                <h5>{{$designation}}</h5>
                <h5>{{$doptor}}</h5>
            </div>
        </div>
    </x-filament::card>
</x-filament::widget>

<div class="filament-widget col-span-2 filament-stats-overview-widget">
    @php
    $cardCount=
    [
        'doptors'=>1,
        'departments'=>\App\Models\Department::count(),
        'designations'=>\App\Models\Designation::count(),
        'employees'=>\App\Models\Employee::count(),
    ];
    @endphp
    <div class="filament-stats grid gap-4 lg:gap-8 grid-cols-2 md:grid-cols-4">
        @foreach($cardCount as $name => $val)
        <a class="filament-stats-card relative p-3 rounded-2xl bg-white shadow dark:bg-gray-800 filament-stats-overview-widget-card" href="admin/{{$name}}">
            <div class="space-y-2">
                <div class="flex items-center space-x-2 rtl:space-x-reverse text-gray-500 dark:text-gray-200"><span>{{ucfirst($name)}}</span></div>
                <div class="text-sm">{{$val}}</div>
            </div>
        </a>
        @endforeach
    </div>
</div>

<!-- direct holiday calendar image -->
<!-- <x-filament::widget class="filament-account-widget col-span-2 flex justify-center">
    <img src="https://dhakadon.com/wp-content/uploads/Bangladesh-Government-Holiday-2023-Calendar.jpg" alt="" srcset="" width="500px" height="500px">
</x-filament::widget> -->

<!-- holiday calendar -->
<x-filament::widget class="filament-account-widget col-span-2 px-0">
    @include('filament.resources.attendance-list-resource.pages.attendance-calendar')
</x-filament::widget>

<style>
    /* calendar */
    .container-calendar {
        padding: 0;
    }

    .modal-button {
        display: none;
    }

    .buttonApprove {
        display: none;
    }

    .date-picker p {
        display: none;
    }

    .bg-rmv {
        background-color: transparent !important;
    }

    /* calendar */
</style>