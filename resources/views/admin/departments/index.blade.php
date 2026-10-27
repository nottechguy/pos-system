@extends('layouts.dashboard')

@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>{{ __('messages.dashboard.departments.headline') }}</h2>
        <p>{{ __('messages.dashboard.departments.description') }}</p>
    </div>
    <div class="dashboard-header__actions">
        <x-bmdc-button type="link" href="{{ route('admin.departments.create') }}" variant="unelevated">
            <x-slot name="mIcon">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#ffffff"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            </x-slot>
            {{ __('messages.dashboard.departments.new_department_button') }}
        </x-bmdc-button>
    </div>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>{{ __('messages.dashboard.departments.table.code') }}</th>
            <th>{{ __('messages.dashboard.departments.table.name') }}</th>
            <th>{{ __('messages.dashboard.departments.table.responsable') }}</th>
            <th>{{ __('messages.dashboard.departments.table.email') }}</th>
            <th>{{ __('messages.dashboard.departments.table.status') }}</th>
            <th>{{ __('messages.dashboard.departments.table.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse($departments as $department)
        <tr>
            <td>{{ $department->code }}</td>
            <td><strong>{{ $department->name }}</strong></td>
            <td>{{ $department->responsibleUser ? $department->responsibleUser->name : __('messages.dashboard.departments.unassigned') }}</td>
            <td>{{ $department->email ?: '-' }}</td>
            <td>
                <span style="color: {{ $department->is_active ? 'green' : 'red' }}; font-weight: bold;">
                    {{ $department->is_active ? __('messages.dashboard.departments.status_active') : __('messages.dashboard.departments.status_inactive') }}
                </span>
            </td>
            <td class="action-links" data-jscontroller="DepartmentsUIController">
                <a href="{{ route('admin.departments.edit', $department->id) }}">
                    <x-table-action-button type="button" class="" action="edit">{{ __('messages.edit') }}</x-table-action-button>
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align: center; padding: 20px;">{{ __('messages.dashboard.departments.no_departments_found') }}.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top: 20px;">
    {{ $departments->links() }}
</div>
@endsection