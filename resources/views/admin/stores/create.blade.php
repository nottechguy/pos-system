@extends('layouts.dashboard')

@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>{{ __('messages.dashboard.stores.headline') }}</h2>
        <p>{{ __('messages.dashboard.stores.description') }}</p>
    </div>
    <div class="dashboard-header__actions">
        <x-bmdc-button type="link" href="{{ route('admin.stores.index') }}" variant="outlined">
            <x-slot name="mIcon">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>
            </x-slot>
            {{ __('messages.dashboard.stores.back') }}
        </x-bmdc-button>
    </div>
</div>

<div class="form-panel">
    <form action="{{ route('admin.stores.store') }}" method="POST">
        @csrf
        @method('POST')

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="code">{{ __('messages.dashboard.stores.form.store_code') }} *</label>
                    <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" required>
                    @error('code') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="name">{{ __('messages.dashboard.stores.form.store_name') }} *</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="phone_line_1">{{ __('messages.dashboard.stores.form.phone') }}</label>
                    <input type="text" name="phone_line_1" id="phone_line_1" class="form-control @error('phone_line_1') is-invalid @enderror" value="{{ old('phone_line_1') }}">
                    @error('phone_line_1') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="email">{{ __('messages.dashboard.stores.form.email') }}</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                    @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="address_line_1">{{ __('messages.dashboard.stores.form.address') }}</label>
            <input type="text" name="address_line_1" id="address_line_1" class="form-control @error('address_line_1') is-invalid @enderror" value="{{ old('address_line_1') }}">
            @error('address_line_1') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="city">{{ __('messages.dashboard.stores.form.city') }}</label>
                    <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}">
                    @error('city') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="state">{{ __('messages.dashboard.stores.form.state') }}</label>
                    <input type="text" name="state" id="state" class="form-control @error('state') is-invalid @enderror" value="{{ old('state') }}">
                    @error('state') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="manager_user_id">{{ __('messages.dashboard.stores.form.manager') }}</label>
                    <select name="manager_user_id" id="manager_user_id" class="form-control @error('manager_user_id') is-invalid @enderror">
                        <option value="">-- {{ __('messages.dashboard.stores.unassigned') }} --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('manager_user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->first_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('manager_user_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group" style="padding-top: 30px;">
                    <x-bmdc-form-field>
                        <x-bmdc-checkbox name="is_active" value="1" checked label="{{ __('messages.dashboard.stores.form.mark_as_active') }}" />
                    </x-bmdc-form-field>
                </div>
            </div>
        </div>

        <div class="form-group form-group-send">
            <x-bmdc-button type="submit" variant="raised">
                {{ __('messages.dashboard.stores.form.create') }}
            </x-bmdc-button>
        </div>
    </form>
</div>
@endsection