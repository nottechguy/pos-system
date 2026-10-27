@php
    $currentUser = auth()->user();
    $avatarUrl = $currentUser->avatar_path ? Storage::url($currentUser->avatar_path) : null;
    // Terminal info can be passed or fetched from session/auth
    $terminalName = session('active_terminal_name', 'Terminal 1');
@endphp

<div class="appbar__section appbar__section--cashier" data-jscontroller="POSCashierController" data-jscbi="[init, 0, null, user_{{ $currentUser->id }}]">
    <div class="pos-terminal-badge" style="display: flex; align-items: center; gap: 0.5rem; margin-right: 1rem; color: #fff; font-size: 0.9rem;">
        <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#FFFFFF"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm40-80h80v-80h-80v80Zm160 0h80v-80h-80v80Zm160 0h80v-80h-80v80ZM200-640h560v-80H200v80Zm0 0v-80 80Z"/></svg>
        <span>{{ $terminalName }}</span>
    </div>

    <div class="cashier-profile" style="display: flex; align-items: center; gap: 0.75rem;">
        <div class="cashier-avatar" style="width: 36px; height: 36px; border-radius: 50%; overflow: hidden; background: #4b5563; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: bold;">
            @if($avatarUrl)
                <img src="{{ $avatarUrl }}" alt="{{ $currentUser->full_name }}" style="width: 100%; height: 100%; object-fit: cover;">
            @else
                <span>{{ strtoupper(substr($currentUser->first_name, 0, 1)) }}</span>
            @endif
        </div>

        <div class="cashier-details" style="display: flex; flex-direction: column;">
            <select id="pos_cashier_selector" class="form-control" data-jscontroller="POSCashierController" data-jschange="@onCashierSelect" style="background: rgba(255,255,255,0.1); color: #fff; border: 1px solid rgba(255,255,255,0.2); padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.9rem; cursor: pointer;">
                <option value="{{ $currentUser->id }}" selected style="color: #000000">{{ $currentUser->first_name }}</option>
            </select>
        </div>
    </div>
</div>