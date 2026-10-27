<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" id="csrf_token" content="{{ csrf_token() }}">
    
    <!-- Assuming your printer server is local -->
    <meta name="printer-url" id="printer_url" content="http://127.0.0.1:3100">

    <title></title>
</head>
<body class="pos-fullscreen-body">

    <main id="main_content" style="display: none; height: 100vh; overflow: hidden;">
        @yield('content')
    </main>

    <script src="{{ asset('js/base.js') }}"></script>
    <script src="{{ asset('js/components.js') }}"></script>

    <script>
    configModulePaths({
        baseUrl: "{{ asset('js') }}",
        paths: {
            'ScaleUIController': "{{ asset('js/modules/scale_ui_module.js') }}"
        }
    });
    </script>

    <script type="text/javascript">
        requireModule("ResourceLoader")
        .register(
            { type: "stylesheet", src: "{{ asset('css/components.css') }}", preload: true },
            { type: "stylesheet", src: "{{ asset('css/pos.css') }}", preload: true }
        )
        .onReady(function () {
            document.body.classList.add("app-ready");
            document.getElementById("main_content").style.display = "flex";
            requireModule("JSEventDispatcher").init();
        })
        .start();
    </script>
</body>
</html>