@extends('layouts.dashboard')

@push('scripts')
<script src="{{ asset('js/modules/terminals_module.js') }}"></script>
@endpush

@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>{{ __('messages.dashboard.pos_terminals.headline_create') }}</h2>
        <p style="color: var(--text-muted);">{{ __('messages.dashboard.pos_terminals.description_create') }}: <strong>{{ $store->name }}</strong></p>
    </div>
    <div class="dashboard-header__actions">
        <x-bmdc-button type="link" href="{{ route('admin.stores.terminals.index', $store->id) }}" variant="outlined">
            <x-slot name="mIcon">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>
            </x-slot>
            {{ __('messages.dashboard.pos_terminals.back_to_terminals_button') }}
        </x-bmdc-button>
    </div>
</div>

<div class="form-panel">
    <form action="{{ route('admin.stores.terminals.store', $store->id) }}" method="POST">
        @csrf

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="code">{{ __('messages.dashboard.pos_terminals.form.code') }} *</label>
                    <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" autocomplete="off" required>
                    @error('code') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="name">{{ __('messages.dashboard.pos_terminals.form.name') }} *</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="{{ __('messages.dashboard.pos_terminals.form.name_placeholder') }}" autocomplete="off" required>
                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="printer_name">{{ __('messages.dashboard.pos_terminals.form.target_printer') }}</label>
            <input type="text" name="printer_name" id="printer_name" class="form-control @error('printer_name') is-invalid @enderror" value="{{ old('printer_name') }}" autocomplete="off" placeholder="{{ __('messages.dashboard.pos_terminals.form.target_printer_placeholder') }}">
            @error('printer_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
            <small style="color: var(--text-muted);">{{ __('messages.dashboard.pos_terminals.form.target_printer_description') }}</small>
        </div>

        <div class="form-group" style="padding-top: 20px;">
            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                {{ __('messages.dashboard.pos_terminals.form.terminal_active_description') }}
            </label>
        </div>

        <div class="form-group form-group-send">
            <x-bmdc-button type="submit" variant="raised">
                {{ __('messages.dashboard.pos_terminals.form.save_terminal') }}
            </x-bmdc-button>
        </div>
    </form>
</div>
@endsection