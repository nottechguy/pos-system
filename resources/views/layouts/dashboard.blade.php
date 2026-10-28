<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta id="csrf_token" name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Dashboard</title>
    <link rel="preload" as="style" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="preload" as="style" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>

<div class="app-container">
    <!-- Sidebar -->
    @include('components.admin.sidebar')

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Appbar -->
        @include('components.admin.appbar')

        @if(session('success'))
            <x-bmdc-snackbar id="snackbar_action_response" label="{{ session('success') }}" leading />
        @endif


        @if(session('error'))
            <x-bmdc-snackbar id="snackbar_action_response" label="{{ session('error') }}" leading />
        @endif

        <!-- Dynamic Content -->
        <main class="main-content" id="main_content">
            @yield('content')
        </main>
    </div>
</div>

<!-- Base JS Framework -->
<script src="{{ asset('js/base.js') }}"></script>
<!-- You can bundle your controllers into components.js -->
<script src="{{ asset('js/components.js') }}"></script>

<script src="{{ asset('js/modules/dashboard.js') }}"></script>
<script src="{{ asset('js/modules/DialogManager.js') }}"></script>
<script src="{{ asset('js/modules/PosCashierController.js') }}"></script>
<script>requireModule("POSCashierController").init();</script>

@stack('scripts')

<script>
    configModulePaths({
        baseUrl: "{{ asset('js') }}",
        paths: {
            'DashboardUIController': "{{ asset('js/modules/business_module.js') }}",
            'BusinessUIController': "{{ asset('js/modules/business_module.js') }}",
            'DepartmentsUIController': "{{ asset('js/modules/departments_module.js') }}",
            'StoresUIController': "{{ asset('js/modules/stores_module.js') }}",
            'TerminalsUIController': "{{ asset('js/modules/terminals_module.js') }}",
            'ProductsUIController': "{{ asset('js/modules/products_module.js') }}",
            'LayoutController': "{{ asset('js/modules/dashboard.js') }}",
        }
    });
</script>

<script type="text/javascript">
    requireModule("ResourceLoader")
        .register(
        )
        .onReady(function () {
            document.body.classList.add("app-ready");
            // Show main content once assets are loaded
            document.getElementById("main_content").style.display = "block";
            
            // Initialize your custom DOM event delegation
            requireModule("JSEventDispatcher").init();
        })
        .start();
</script>
</body>
</html>