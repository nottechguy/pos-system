@extends('layouts.dashboard')

@push('scripts')
<script src="{{ asset('js/modules/stores_module.js') }}"></script>
@endpush

@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>{{ __('messages.dashboard.stores.edit_store') }}: {{ $store->name }}</h2>
    </div>
    <div class="dashboard-header__actions">
        <x-bmdc-button type="link" href="{{ route('admin.stores.index') }}" variant="outlined">
            <x-slot name="mIcon">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>
            </x-slot>
            {{ __('messages.dashboard.stores.back') }}
        </x-bmdc-button>
        <form class="delete-form" action="{{ route('admin.stores.destroy', $store->id) }}" method="POST" style="display:inline;" onsubmit="requireModule('StoresUIController').confirmDeletion(event)">
            @csrf
            @method('DELETE')

            <x-bmdc-button type="submit" variant="unelevated">
                <x-slot name="mIcon">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M280-120q-33 0-56.5-23.5T200-200v-520q-17 0-28.5-11.5T160-760q0-17 11.5-28.5T200-800h160q0-17 11.5-28.5T400-840h160q17 0 28.5 11.5T600-800h160q17 0 28.5 11.5T800-760q0 17-11.5 28.5T760-720v520q0 33-23.5 56.5T680-120H280Zm148.5-171.5Q440-303 440-320v-280q0-17-11.5-28.5T400-640q-17 0-28.5 11.5T360-600v280q0 17 11.5 28.5T400-280q17 0 28.5-11.5Zm160 0Q600-303 600-320v-280q0-17-11.5-28.5T560-640q-17 0-28.5 11.5T520-600v280q0 17 11.5 28.5T560-280q17 0 28.5-11.5Z"/></svg>
                </x-slot>
                {{ __('messages.dashboard.stores.delete') }}
            </x-bmdc-button>
        </form>
    </div>
</div>

<div class="form-panel">
    <form action="{{ route('admin.stores.update', $store->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="code">{{ __('messages.dashboard.stores.form.store_code') }} *</label>
                    <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $store->code) }}" required>
                    @error('code') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="name">{{ __('messages.dashboard.stores.form.store_name') }} *</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $store->name) }}" required>
                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="phone_line_1">{{ __('messages.dashboard.stores.form.phone') }}</label>
                    <input type="text" name="phone_line_1" id="phone_line_1" class="form-control @error('phone_line_1') is-invalid @enderror" value="{{ old('phone_line_1', $store->phone_line_1) }}">
                    @error('phone_line_1') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="email">{{ __('messages.dashboard.stores.form.email') }}</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $store->email) }}">
                    @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="address_line_1">{{ __('messages.dashboard.stores.form.address') }}</label>
            <input type="text" name="address_line_1" id="address_line_1" class="form-control @error('address_line_1') is-invalid @enderror" value="{{ old('address_line_1', $store->address_line_1) }}">
            @error('address_line_1') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="city">{{ __('messages.dashboard.stores.form.city') }}</label>
                    <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city', $store->city) }}">
                    @error('city') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="state">{{ __('messages.dashboard.stores.form.state') }}</label>
                    <input type="text" name="state" id="state" class="form-control @error('state') is-invalid @enderror" value="{{ old('state', $store->state) }}">
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
                            <option value="{{ $user->id }}" {{ old('manager_user_id', $store->manager_user_id) == $user->id ? 'selected' : '' }}>
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
                        <x-bmdc-checkbox name="is_active" value="1" :checked="old('is_active', $store->is_active)" label="{{ __('messages.dashboard.stores.form.mark_as_active') }}" />
                    </x-bmdc-form-field>
                </div>
            </div>
        </div>

        <div class="form-group form-group-send">
            <x-bmdc-button type="submit" variant="raised">{{ __('messages.dashboard.stores.form.update') }}</x-bmdc-button>
        </div>
    </form>
</div>

<!-- RELATED MODULE CARDS -->
<div class="cards-grid">
    
    <!-- POS Terminals Card -->
    <div class="module-card">
        <h3>{{ __('messages.dashboard.stores.cards.pos_terminals.title') }}</h3>
        <p>{{ __('messages.dashboard.stores.cards.pos_terminals.description') }}</p>
        
        <ul>
            @forelse($store->terminals as $terminal)
                <li>
                    <strong>{{ $terminal->name }}</strong> ({{ $terminal->is_active ? __('messages.dashboard.stores.cards.pos_terminals.status_online') : __('messages.dashboard.stores.cards.pos_terminals.status_offline')  }})
                </li>
            @empty
                <li>{{ __('messages.dashboard.stores.cards.pos_terminals.no_terminals_configured') }}</li>
            @endforelse
        </ul>
        
        <a href="{{ route('admin.stores.terminals.index', $store->id) }}" class="btn btn-secondary" style="text-align: center; margin-top: auto;">{{ __('messages.dashboard.stores.cards.pos_terminals.manage_terminals') }}</a>
    </div>

    <!-- Assigned Staff Card -->
    <!--<div class="module-card">
        <h3>Assigned Staff</h3>
        <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 15px;">Employees granted access to this store.</p>
        
        <ul style="list-style: none; padding: 0; margin-bottom: 15px;">
            @forelse($store->users as $staff)
                <li style="padding: 8px 0; border-bottom: 1px solid #eee; font-size: 0.9rem;">
                    {{ $staff->name }} <span style="color: #999; font-size: 0.8rem;">- {{ $staff->pivot->job_title ?? 'Staff' }}</span>
                </li>
            @empty
                <li style="font-size: 0.9rem; color: #999;">No staff assigned.</li>
            @endforelse
        </ul>
        
        <a href="#" class="btn btn-secondary" style="text-align: center; margin-top: auto;">Manage Staff Access</a>
    </div>-->

</div>
@endsection