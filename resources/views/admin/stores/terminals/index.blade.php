@extends('layouts.dashboard')

@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>{{ __('messages.dashboard.pos_terminals.headline') }}: {{ $store->name }}</h2>
        <p>{{ __('messages.dashboard.pos_terminals.description') }}</p>
    </div>
    <div class="dashboard-header__actions">
        <x-bmdc-button type="link" href="{{ route('admin.stores.edit', $store->id) }}" variant="outlined">
            <x-slot name="mIcon">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>
            </x-slot>
            {{ __('messages.dashboard.stores.back') }}
        </x-bmdc-button>
        <x-bmdc-button type="link" href="{{ route('admin.stores.terminals.create', $store->id) }}" variant="unelevated">
                <x-slot name="mIcon">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#ffffff"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                </x-slot>
                {{ __('messages.dashboard.pos_terminals.new_terminal_button') }}
        </x-bmdc-button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<!-- GLOBAL VALIDATION ERRORS CATCHER -->
@if ($errors->any())
    <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
        <strong style="display: block; margin-bottom: 8px;">Please check the following errors before proceeding:</strong>
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li style="margin-bottom: 3px;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<table class="data-table">
    <thead>
        <tr>
            <th>{{ __('messages.dashboard.pos_terminals.table.code') }}</th>
            <th>{{ __('messages.dashboard.pos_terminals.table.name') }}</th>
            <th>{{ __('messages.dashboard.pos_terminals.table.printer') }}</th>
            <th>{{ __('messages.dashboard.pos_terminals.table.last_seen') }}</th>
            <th>{{ __('messages.dashboard.pos_terminals.table.status') }}</th>
            <th>{{ __('messages.dashboard.pos_terminals.table.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse($terminals as $terminal)
        <tr>
            <td>{{ $terminal->code }}</td>
            <td><strong>{{ $terminal->name }}</strong></td>
            <td>{{ $terminal->printer_name ?: __('messages.dashboard.pos_terminals.printer_default') }}</td>
            <td>
                @if($terminal->last_seen_at)
                    {{ $terminal->last_seen_at->diffForHumans() }}
                @else
                    <span style="color: var(--text-muted);">{{ __('messages.dashboard.pos_terminals.status_never_connected') }}</span>
                @endif
            </td>
            <td>
                <span style="color: {{ $terminal->is_active ? 'green' : 'red' }}; font-weight: bold;">
                    {{ $terminal->is_active ? 'Online' : 'Offline' }}
                </span>
            </td>
            <td class="action-links" data-jscontroller="TerminalsUIController">
                <a href="{{ route('admin.stores.terminals.edit', [$store->id, $terminal->id]) }}">
                    <x-table-action-button type="button" class="" action="edit">{{ __('messages.edit') }}</x-table-action-button>
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align: center; padding: 20px;">No terminals configured for this store.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top: 20px;">
    {{ $terminals->links() }}
</div>
@endsection