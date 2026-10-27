@extends('layouts.dashboard')

@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>{{ __('messages.dashboard.departments.edit_departments') }}: {{ $department->name }}</h2>
    </div>
    <div class="dashboard-header__actions">
        <x-bmdc-button type="link" href="{{ route('admin.departments.index') }}" variant="outlined">
            <x-slot name="mIcon">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>
            </x-slot>
            {{ __('messages.dashboard.departments.back') }}
        </x-bmdc-button>
        <form class="delete-form" action="{{ route('admin.departments.destroy', $department->id) }}" method="POST" style="display:inline;" onsubmit="requireModule('StoresUIController').confirmDeletion(event)">
            @csrf
            @method('DELETE')

            <x-bmdc-button type="submit" variant="unelevated">
                <x-slot name="mIcon">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M280-120q-33 0-56.5-23.5T200-200v-520q-17 0-28.5-11.5T160-760q0-17 11.5-28.5T200-800h160q0-17 11.5-28.5T400-840h160q17 0 28.5 11.5T600-800h160q17 0 28.5 11.5T800-760q0 17-11.5 28.5T760-720v520q0 33-23.5 56.5T680-120H280Zm148.5-171.5Q440-303 440-320v-280q0-17-11.5-28.5T400-640q-17 0-28.5 11.5T360-600v280q0 17 11.5 28.5T400-280q17 0 28.5-11.5Zm160 0Q600-303 600-320v-280q0-17-11.5-28.5T560-640q-17 0-28.5 11.5T520-600v280q0 17 11.5 28.5T560-280q17 0 28.5-11.5Z"/></svg>
                </x-slot>
                {{ __('messages.dashboard.departments.delete') }}
            </x-bmdc-button>
        </form>
    </div>
</div>

<div class="form-panel">
    <form action="{{ route('admin.departments.update', $department->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="code">{{ __('messages.dashboard.departments.form.code') }} *</label>
                    <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $department->code) }}" required>
                    @error('code') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="name">{{ __('messages.dashboard.departments.form.name') }} *</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $department->name) }}" required>
                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label for="description">{{ __('messages.dashboard.departments.form.description') }}</label>
            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $department->description) }}</textarea>
            @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="phone">{{ __('messages.dashboard.departments.form.phone') }}</label>
                    <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $department->phone) }}">
                    @error('phone') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="email">{{ __('messages.dashboard.departments.form.email') }}</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $department->email) }}">
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
                            <option value="{{ $user->id }}" {{ old('responsible_user_id', $department->responsible_user_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('responsible_user_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group" style="padding-top: 30px;">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $department->is_active) ? 'checked' : '' }}>
                        {{ __('messages.dashboard.departments.form.mark_as_active') }}
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group form-group-send">
            <x-bmdc-button type="submit" variant="raised">{{ __('messages.dashboard.departments.form.update') }}</x-bmdc-button>
        </div>
    </form>
</div>

<!-- RELATED MODULE CARDS -->
<div class="cards-grid">
    
    <!-- Product Categories Card -->
    <div class="module-card">
        <h3>Product Categories</h3>
        <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 15px;">Categories grouped under this department.</p>
        
        <ul style="list-style: none; padding: 0; margin-bottom: 15px;">
            @forelse($department->categories as $category)
                <li style="padding: 8px 0; border-bottom: 1px solid #eee; font-size: 0.9rem; display: flex; justify-content: space-between;">
                    <span><strong>{{ $category->code }}</strong> - {{ $category->name }}</span>
                    <span style="color: {{ $category->is_active ? 'green' : 'red' }};">{{ $category->is_active ? 'Active' : 'Inactive' }}</span>
                </li>
            @empty
                <li style="font-size: 0.9rem; color: #999;">No categories assigned to this department.</li>
            @endforelse
        </ul>
        
        <!-- Assuming a route exists to view/create categories filtered by this department -->
        <a href="#" class="btn btn-secondary" style="text-align: center; margin-top: auto;">Manage Categories</a>
    </div>

</div>
@endsection