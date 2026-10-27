<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta id="csrf_token" name="csrf-token" content="{{ csrf_token() }}">
    <title>Business Setup</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="auth-container" style="max-width: 500px;">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Initial Setup</h1>
                <p>Let's configure your primary business information before proceeding to the dashboard.</p>
            </div>

            <form action="{{ route('setup.business.store') }}" method="POST"
                  data-jscontroller="BusinessSetupController"
                  data-jsinstance="true"
                  data-on-beforesubmit="@onBeforeSubmit"
                  data-on-success="@onSuccess"
                  data-on-error="@onError"
                  onsubmit="new(requireModule('SubmitNativeEvent'))(event, this, 'async'); return false;">
                
                <div class="form-group">
                    <label for="name">Business Name</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="e.g. Abarrotes Todo En Uno" required>
                </div>

                <div class="form-group">
                    <label for="legal_name">Legal Name (Razón Social)</label>
                    <input type="text" id="legal_name" name="legal_name" class="form-control" placeholder="e.g. Abarrotes Todo En Uno S.A. de C.V.">
                </div>

                <div class="form-group">
                    <label for="tax_id">RFC</label>
                    <input type="text" id="tax_id" name="tax_id" class="form-control" placeholder="e.g. XAXX010101000" required>
                </div>

                <div class="form-group">
                    <label for="phone_line_1">Contact Phone</label>
                    <input type="text" id="phone_line_1" name="phone_line_1" class="form-control" placeholder="(Optional)">
                </div>
                
                <div class="form-group">
                    <label for="email">Business Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="(Optional)">
                </div>

                <button type="submit" class="btn-primary">Complete Setup</button>
            </form>
        </div>
    </div>

    <!-- Framework Initialization -->
    <script src="{{ asset('js/base.js') }}"></script>
    <script src="{{ asset('js/components.js') }}"></script>
    <script type="text/javascript">
        requireModule("JSEventDispatcher").init();
    </script>
</body>
</html>