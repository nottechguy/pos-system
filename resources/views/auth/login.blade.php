<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta id="csrf_token" name="csrf-token" content="{{ csrf_token() }}">
    <title>Punto de Venta - Abarrotes Todo En Uno</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body class="animated-gradient">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <img src="{{ asset('storage/img/logo_x1.png') }}" height="180px" alt="">
                <h1>{{ config('app.name') }}</h1>
                <p>{{ __('messages.auth.signin_to_continue') }}</p>
            </div>

            <form action="{{ route('login.post') }}" method="POST"
                  data-jscontroller="AuthLoginController"
                  data-jsinstance="true"
                  data-on-beforesubmit="@onBeforeSubmit"
                  data-on-success="@onSuccess"
                  data-on-error="@onError"
                  onsubmit="new(requireModule('SubmitNativeEvent'))(event, this, 'async'); return false;">
                
                <div class="form-group">
                    <label for="email">{{ __('messages.auth.email') }}</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                    <div id="error_email" class="error-text"></div>
                </div>

                <div class="form-group">
                    <label for="password">{{ __('messages.auth.password') }}</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                    <div id="error_password" class="error-text"></div>
                </div>

                <button type="submit" class="btn-primary submit-btn">{{ __('messages.auth.signin_button') }}</button>
            </form>
        </div>
    </div>

    <!-- Framework Initialization -->
    <script src="{{ asset('js/base.js') }}"></script>
    <script src="{{ asset('js/components.js') }}"></script>
    <script>
    configModulePaths({
        baseUrl: "{{ asset('js') }}",
        paths: {
        },
        bundles: {
            "{{ asset('js/login.js') }}": ["AuthLoginController"]
        }
    });
    </script>
    <script type="text/javascript">
    requireModule("ResourceLoader")
    .register(
        { type: "script", src: "{{ asset('js/login.js') }}", async: true }
    )
    .onReady(function () {
        document.body.classList.add("app-ready");
        requireModule("JSEventDispatcher").init();
    })
    .start();
</script>
</body>
</html>