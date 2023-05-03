<ul class="app-aside-menus">

    <li class="app-aside-menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <a href="{{route('admin.dashboard')}}">
            <img src="{{asset('icons/dashboard.webp')}}"> Dashboard
        </a>
    </li>

    <li class="app-aside-menu-item">
        <a href="/admin">
            <img src="{{asset('icons/dashboard.webp')}}"> Filament
        </a>
    </li>

    <!-- <li class="app-aside-menu-item {{ request()->routeIs('module') ? 'active' : '' }}">
        <a href="#">
            <img src="{{asset('icons/setting.webp')}}"> Settings
        </a>
    </li> -->


    <li class="app-aside-menu-item dropdown {{ 
        isRoutes([
        'admin.settings.office.list', 
        'admin.settings.office.add', 
        'admin.settings.roles', 
        'admin.settings.permissions', 
        'admin.setting.users',
        'admin.settings.designations',
        'admin.settings.departments',
        ]) ? 'active' : '' }}">
        <a href="#">
            <img src="{{asset('icons/setting.webp')}}">
            <span class="menu-title">App Settings</span>
        </a>
        <ul>
            <li>
                <a class="{{ isRoutes(['admin.settings.office.list', 'admin.settings.office.add']) ? 'active' : '' }}"
                    href="{{route('admin.settings.office.list')}}">Offices</a>
            </li>

            <li>
                <a class="{{ isRoutes(['admin.settings.designations']) ? 'active' : '' }}"
                    href="{{route('admin.settings.designations')}}">Designations</a>
            </li>

            <li>
                <a class="{{ isRoutes(['admin.settings.departments']) ? 'active' : '' }}"
                    href="{{route('admin.settings.departments')}}">Departments</a>
            </li>

            <li>
                <a class="{{ isRoutes(['admin.settings.roles']) ? 'active' : '' }}"
                    href="{{route('admin.settings.roles')}}">Roles</a>
            </li>

            <li>
                <a class="{{ isRoutes(['admin.settings.permissions']) ? 'active' : '' }}"
                    href="{{route('admin.settings.permissions')}}">Permissions</a>
            </li>

            <li>
                <a class="{{isRoutes(['admin.setting.users']) ? 'active' : '' }}"
                    href="{{route('admin.setting.users')}}">Users</a>
            </li>
        </ul>
    </li>

</ul>