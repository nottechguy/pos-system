@extends('layouts.dashboard')

@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>{{ __('messages.dashboard.departments.headline_create') }}</h2>
    </div>
    <div class="dashboard-header__actions">
        <x-bmdc-button type="link" href="{{ route('admin.departments.index') }}" variant="outlined">
            <x-slot name="mIcon">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>
            </x-slot>
            {{ __('messages.dashboard.departments.back') }}
        </x-bmdc-button>
    </div>
</div>

<div class="form-panel">
    <form action="{{ route('admin.departments.store') }}" method="POST">
        @csrf
        @method('POST')

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="code">{{ __('messages.dashboard.departments.form.code') }} *</label>
                    <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" required autocomplete="off">
                    @error('code') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="name">{{ __('messages.dashboard.departments.form.name') }} *</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autocomplete="off">
                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label for="description">{{ __('messages.dashboard.departments.form.description') }}</label>
            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
            @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="phone">{{ __('messages.dashboard.departments.form.phone') }}</label>
                    <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" autocomplete="off">
                    @error('phone') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="email">{{ __('messages.dashboard.departments.form.email') }}</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" autocomplete="off">
                    @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="responsible_user_id">{{ __('messages.dashboard.departments.form.responsable') }}</label>
                    <select name="responsible_user_id" id="responsible_user_id" class="form-control @error('responsible_user_id') is-invalid @enderror">
                        <option value="">-- {{ __('messages.dashboard.departments.unassigned') }} --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('responsible_user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->first_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('responsible_user_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group" style="padding-top: 30px;">
                    <x-bmdc-form-field>
                        <x-bmdc-checkbox name="is_active" value="1" checked label="{{ __('messages.dashboard.departments.form.mark_as_active') }}" />
                    </x-bmdc-form-field>
                </div>
            </div>
        </div>

        <div class="form-group form-group-send">
            <x-bmdc-button type="submit" variant="raised">{{ __('messages.dashboard.departments.form.create') }}</x-bmdc-button>
        </div>
    </form>
</div>
@endsection