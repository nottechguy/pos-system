<header class="appbar">
    <div class="appbar-left">
        <!-- We use your framework's JSController approach to toggle the sidebar on mobile -->
        <button type="button" class="menu-toggle" data-jscontroller="LayoutController" data-jsclick="@toggleSidebar">
            &#9776;
        </button>
        <h2>{{ config('app.name') }}</h2>
    </div>

    <div class="appbar-right">
        <div class="profile-switcher">
        </div>
        
        <form action="{{ route('admin.logout') }}" method="POST" onsubmit="new(requireModule('SubmitNativeEvent'))(event, this, 'sync'); return false;">
            @csrf
            <button type="submit" class="btn-logout">{{ __('messages.logout') }}</button>
        </form>
    </div>
</header>