<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <!-- Required for AsyncRequest module -->
    <meta name="csrf-token" id="csrf_token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Point of Sale')</title>
</head>

<body class="pos-fullscreen-body">

    <!-- Hide main content until ResourceLoader finishes -->
    <main id="main_content" style="display: none; height: 100vh; overflow: hidden; flex-direction: column;">
        @yield('content')
    </main>

    <!-- Core Module System -->
    <script src="{{ asset('js/base.js') }}"></script>
    <script src="{{ asset('js/components.js') }}"></script>
    <script src="{{ asset('js/modules/scanner_module.js') }}"></script>
    <script src="{{ asset('js/modules/KeyboardShortcuts.js') }}"></script>
    <script src="{{ asset('js/modules/PosStorageManager.js') }}"></script>
    <script src="{{ asset('js/modules/PosUIController.js') }}"></script>
    <script src="{{ asset('js/modules/PosCashierController.js') }}"></script>
    <script>requireModule("PosUIController").init();</script>
    <script>requireModule("POSCashierController").init();</script>

    <!-- Module Configuration -->
    <script>
    configModulePaths({
        baseUrl: "{{ asset('js') }}",
        paths: {
            'GlobalBarcodeScanner': "{{ asset('js/modules/scanner_module.js') }}",
            'KeyboardShortcuts': "{{ asset('js/modules/KeyboardShortcuts.js') }}",
            'PosUIController': "{{ asset('js/modules/PosUIController.js') }}",
            'POSCashierController': "{{ asset('js/modules/PosCashierController.js') }}"
        }
    });
    </script>

    <!-- Asset Loading & Bootstrapping -->
    <script type="text/javascript">
        requireModule("ResourceLoader")
        .register(
            { type: "stylesheet", src: "{{ asset('css/components.css') }}", preload: true, crossOrigin: 'anonymous' },
            { type: "stylesheet", src: "{{ asset('css/pos.css') }}", preload: true, crossOrigin: 'anonymous' }
        )
        .onReady(function () {
            document.body.classList.add("app-ready");
            document.getElementById("main_content").style.display = "flex"; // or block, depending on your pos.css
            requireModule("JSEventDispatcher").init();
        })
        .start();
    </script>

    @stack('scripts')
</body>
</html>