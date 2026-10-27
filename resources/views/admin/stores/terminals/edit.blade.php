@extends('layouts.dashboard')

@push('scripts')
<script src="{{ asset('js/modules/terminals_module.js') }}"></script>
@endpush

@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>{{ __('messages.dashboard.pos_terminals.headline_edit') }}: {{ $terminal->name }}</h2>
        <p style="color: var(--text-muted);">{{ __('messages.dashboard.pos_terminals.description_edit') }}: <strong>{{ $store->name }}</strong></p>
    </div>
    <div class="dashboard-header__actions">
        <x-bmdc-button type="link" href="{{ route('admin.stores.terminals.index', $store->id) }}" variant="outlined">
            <x-slot name="mIcon">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>
            </x-slot>
            {{ __('messages.dashboard.stores.back') }}
        </x-bmdc-button>
        <form class="delete-form" action="{{ route('admin.stores.terminals.destroy', [$store->id, $terminal->id]) }}" method="POST" data-jscontroller="TerminalsUIController" onsubmit="requireModule('TerminalsUIController').confirmDeletion(event)">
            @csrf
            @method('DELETE')
            <x-bmdc-button type="submit" variant="unelevated">
                <x-slot name="mIcon">
                     <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M280-120q-33 0-56.5-23.5T200-200v-520q-17 0-28.5-11.5T160-760q0-17 11.5-28.5T200-800h160q0-17 11.5-28.5T400-840h160q17 0 28.5 11.5T600-800h160q17 0 28.5 11.5T800-760q0 17-11.5 28.5T760-720v520q0 33-23.5 56.5T680-120H280Zm148.5-171.5Q440-303 440-320v-280q0-17-11.5-28.5T400-640q-17 0-28.5 11.5T360-600v280q0 17 11.5 28.5T400-280q17 0 28.5-11.5Zm160 0Q600-303 600-320v-280q0-17-11.5-28.5T560-640q-17 0-28.5 11.5T520-600v280q0 17 11.5 28.5T560-280q17 0 28.5-11.5Z"/></svg>
                </x-slot>
                {{ __('messages.dashboard.pos_terminals.delete') }}
            </x-bmdc-button>
        </form>
    </div>
</div>

<div class="form-panel">
    <form action="{{ route('admin.stores.terminals.update', [$store->id, $terminal->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="code">{{ __('messages.dashboard.pos_terminals.form.code') }} *</label>
                    <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $terminal->code) }}" autocomplete="off" required>
                    @error('code') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="name">{{ __('messages.dashboard.pos_terminals.form.name') }} *</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $terminal->name) }}" placeholder="{{ __('messages.dashboard.pos_terminals.form.name_placeholder') }}" autocomplete="off" required>
                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="printer_name">{{ __('messages.dashboard.pos_terminals.form.target_printer') }}</label>
            <input type="text" name="printer_name" id="printer_name" class="form-control @error('printer_name') is-invalid @enderror" value="{{ old('printer_name', $terminal->printer_name) }}" placeholder="{{ __('messages.dashboard.pos_terminals.form.target_printer_placeholder') }}">
            @error('printer_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
            <small style="color: var(--text-muted);">{{ __('messages.dashboard.pos_terminals.form.target_printer_description') }}</small>
        </div>

        <div class="form-group" style="padding-top: 20px;">
            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $terminal->is_active) ? 'checked' : '' }}>
                {{ __('messages.dashboard.pos_terminals.form.terminal_active_description') }}
            </label>
        </div>

        <div class="form-group form-group-send">
            <x-bmdc-button type="submit" variant="raised">
                {{ __('messages.dashboard.pos_terminals.form.update_terminal') }}
            </x-bmdc-button>
        </div>
    </form>
</div>
@endsection