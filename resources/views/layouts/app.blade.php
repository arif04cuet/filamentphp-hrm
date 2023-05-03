<!DOCTYPE html>
<html lang="bn">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="RDCD ERP Solution.">
    <meta name="keywords" content="RDCD ERP Solution">
    <meta name="author" content="OrangeBD Limited.">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- title -->
    <title>ERP</title>
    <!-- // -->
    @include('layouts.partials.style')
    <!-- // -->
    @stack('style')
</head>


<body>
    <div class="app">
        @include('layouts.partials.nav')
        <section class="template">
            <div class="exhauster">
                <div class="app-aside" id="scrollbar">
                    @include('layouts.partials.aside')
                </div>
            </div>
            <div class="app-content">
                <div class="bg-white min85vh">
                    @yield('content')
                </div>
            </div>
        </section>
        @include('layouts.partials.footer')
    </div>
    @include('layouts.partials.script')
    @stack('script')
</body>

</html>
