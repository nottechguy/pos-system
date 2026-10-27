@extends('layouts.dashboard')

@push('scripts')
<script src="{{ asset('js/modules/stores_module.js') }}"></script>
@endpush

@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>{{ __('messages.dashboard.stores.headline') }}</h2>
        <p>{{ __('messages.dashboard.stores.description') }}</p>
    </div>
    <div class="dashboard-header__actions">
        <x-bmdc-button type="link" href="{{ route('admin.stores.create') }}" variant="unelevated">
            <x-slot name="mIcon">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#ffffff"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            </x-slot>
            {{ __('messages.dashboard.stores.new_store_button') }}
        </x-bmdc-button>
    </div>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>{{ __('messages.dashboard.stores.table.code') }}</th>
            <th>{{ __('messages.dashboard.stores.table.name') }}</th>
            <th>{{ __('messages.dashboard.stores.table.city') }}</th>
            <th>{{ __('messages.dashboard.stores.table.manager') }}</th>
            <th>{{ __('messages.dashboard.stores.table.status') }}</th>
            <th>{{ __('messages.dashboard.stores.table.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse($stores as $store)
        <tr>
            <td>{{ $store->code }}</td>
            <td>{{ $store->name }}</td>
            <td>{{ $store->city ?: '-' }}</td>
            <td>{{ $store->manager ? $store->manager->first_name : __('messages.dashboard.stores.unassigned') }}</td>
            <td>
                <span style="color: {{ $store->is_active ? 'green' : 'red' }}; font-weight: bold;">
                    {{ $store->is_active ? __('messages.dashboard.stores.status_active') :  __('messages.dashboard.stores.status_inactive') }}
                </span>
            </td>
            <td class="action-links" data-jscontroller="StoresUIController">
                <a href="{{ route('admin.stores.edit', $store->id) }}">
                    <x-table-action-button type="button" class="" action="edit">{{ __('messages.edit') }}</x-table-action-button>
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align: center; padding: 20px;">{{ __('messages.dashboard.stores.no_stores_found') }}</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top: 20px;">
    {{ $stores->links() }}
</div>
@endsection