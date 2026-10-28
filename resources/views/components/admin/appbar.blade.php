@php
    $currentUser = auth()->user();
    $avatarUrl = $currentUser->avatar_path ? Storage::url($currentUser->avatar_path) : null;
@endphp

<header class="appbar">
    <div class="appbar-left">
        <button type="button" class="menu-toggle" data-jscontroller="LayoutController" data-jsclick="@toggleSidebar">
            &#9776;
        </button>
        <h2>{{ config('app.name') }}</h2>
    </div>

    <div class="appbar-right" style="display: flex; align-items: center; gap: 1rem;">
        <!-- Cashier Component Profile Switcher -->
        <div class="appbar__section appbar__section--cashier" data-jscontroller="POSCashierController" data-jscbi="[init, 0, null, user_{{ $currentUser->id }}]" style="display: flex; align-items: center; gap: 0.75rem;">
            <div class="cashier-avatar" style="width: 36px; height: 36px; border-radius: 50%; overflow: hidden; background: #4b5563; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: bold;">
                @if($avatarUrl)
                    <img src="{{ $avatarUrl }}" alt="{{ $currentUser->full_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <span>{{ strtoupper(substr($currentUser->first_name, 0, 1)) }}</span>
                @endif
            </div>

            <div class="cashier-details" style="display: flex; flex-direction: column;">
                <select id="pos_cashier_selector" class="form-control" data-jscontroller="POSCashierController" data-jschange="@onCashierSelect" style="background: rgba(0,0,0,0.05); color: #333; border: 1px solid #ccc; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.9rem; cursor: pointer;">
                    <option value="{{ $currentUser->id }}" selected>{{ $currentUser->full_name }}</option>
                </select>
            </div>
        </div>
        
        <form action="{{ route('admin.logout') }}" method="POST" onsubmit="new(requireModule('SubmitNativeEvent'))(event, this, 'sync'); return false;">
            @csrf
            <button type="submit" class="btn-logout">{{ __('messages.logout') }}</button>
        </form>
    </div>
</header>