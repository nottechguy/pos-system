@extends('layouts.dashboard')

@push('scripts')
<script src="{{ asset('js/modules/products_module.js') }}"></script>
<script src="{{ asset('js/modules/scanner_module.js') }}"></script>

<script type="text/javascript">
    // Initialize the form scanner, targeting the specific input ID
    document.addEventListener("DOMContentLoaded", function() {
        requireModule('ProductFormScanner').init('primary_barcode');
    });
</script>
@endpush


@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>{{ __('messages.dashboard.products.headline') }}</h2>
        <p>{{ __('messages.dashboard.products.description') }}</p>
    </div>
    <div class="dashboard-header__actions">
        <x-bmdc-button type="link" href="{{ route('admin.products.index') }}" variant="outlined">
            <x-slot name="mIcon">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>
            </x-slot>
            {{ __('messages.dashboard.products.back') }}
        </x-bmdc-button>
    </div>
</div>

<div class="form-panel" data-jscontroller="ProductsUIController">
    <form action="{{ route('admin.products.store') }}" method="POST">
        @csrf

        <div class="form-row">
            <div class="form-col" style="flex-grow: 1.5;">
                <div class="form-group">
                    <label for="primary_barcode">{{ __('messages.dashboard.products.form.barcode') }}</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="text" name="primary_barcode" id="primary_barcode" class="form-control @error('primary_barcode') is-invalid @enderror" value="{{ old('primary_barcode') }}" placeholder="{{ __('messages.dashboard.products.form.barcode') }}" data-jskeydown="@handleBarcodeScan" autofocus autocomplete="off">
                        <button type="button" class="btn btn-secondary" style="white-space: nowrap;" data-jsclick="@generateInternalBarcode">{{ __('messages.dashboard.products.form.barcode_generate_internal') }}</button>
                    </div>
                    @error('primary_barcode') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    <small style="color: var(--text-muted);">{{ __('messages.dashboard.products.form.barcode_helper_text') }}</small>
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="sku">{{ __('messages.dashboard.products.form.sku') }}</label>
                    <input type="text" name="sku" id="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku') }}" autocomplete="off">
                    @error('sku') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="name">{{ __('messages.dashboard.products.form.product_name') }} *</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus="off">
            @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="department_id">{{ __('messages.dashboard.products.form.department') }} *</label>
                    <select name="department_id" id="department_id" class="form-control @error('department_id') is-invalid @enderror" required>
                        <option value="">-- {{ __('messages.dashboard.products.form.select_department') }} --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="category_id">{{ __('messages.dashboard.products.form.category') }}</label>
                    <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror">
                        <option value="">-- {{ __('messages.dashboard.products.form.select_category') }} --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="product_type">{{ __('messages.dashboard.products.form.product_type') }} *</label>
                    <select name="product_type" id="product_type" class="form-control @error('product_type') is-invalid @enderror" required>
                        <option value="standard" {{ old('product_type') == 'standard' ? 'selected' : '' }}>{{ __('messages.dashboard.products.form.product_type_standard') }}</option>
                        <option value="service" {{ old('product_type') == 'service' ? 'selected' : '' }}>{{ __('messages.dashboard.products.form.product_type_service') }})</option>
                        <option value="kit" {{ old('product_type') == 'kit' ? 'selected' : '' }}>{{ __('messages.dashboard.products.form.product_type_kit') }}</option>
                    </select>
                    @error('product_type') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="sale_unit_id">{{ __('messages.dashboard.products.form.uom') }} *</label>
                    <select name="sale_unit_id" id="sale_unit_id" class="form-control @error('sale_unit_id') is-invalid @enderror" required>
                        <option value="">-- {{ __('messages.dashboard.products.form.select_uom') }} --</option>
                        @foreach($units as $u)
                            <option value="{{ $u->id }}" {{ old('sale_unit_id') == $u->id ? 'selected' : '' }}>{{ ucwords(__("messages.measurement_units.{$u->code}.name")) }} ({{ __("messages.measurement_units.{$u->code}.symbol") }})</option>
                        @endforeach
                    </select>
                    @error('sale_unit_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="inventory_unit_id">{{ __('messages.dashboard.products.form.uomi') }} *</label>
                    <select name="inventory_unit_id" id="inventory_unit_id" class="form-control @error('inventory_unit_id') is-invalid @enderror" required>
                        <option value="">-- {{ __('messages.dashboard.products.form.select_uom') }} --</option>
                        @foreach($units as $u)
                            <option value="{{ $u->id }}" {{ old('sale_unit_id') == $u->id ? 'selected' : '' }}>{{ ucwords(__("messages.measurement_units.{$u->code}.name")) }} ({{ __("messages.measurement_units.{$u->code}.symbol") }})</option>
                        @endforeach
                    </select>
                    @error('inventory_unit_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col" style="padding-top: 20px;">
                <label style="display: block; margin-bottom: 10px; cursor: pointer;">
                    <input type="checkbox" name="allows_fractional_qty" value="1" {{ old('allows_fractional_qty') ? 'checked' : '' }}> {{ __('messages.dashboard.products.form.allow_fractional_qty') }}
                </label>
                <label style="display: block; margin-bottom: 10px; cursor: pointer;">
                    <input type="checkbox" name="track_inventory" value="1" {{ old('track_inventory', true) ? 'checked' : '' }}> {{ __('messages.dashboard.products.form.track_inventory') }}
                </label>
                <label style="display: block; margin-bottom: 10px; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}> {{ __('messages.dashboard.products.form.product_is_active') }}
                </label>
            </div>
        </div>

        <div class="form-group form-group-send">
            <x-bmdc-button type="submit" variant="raised">
                {{ __('messages.dashboard.products.form.save_and_continue') }}
            </x-bmdc-button>
        </div>
    </form>
</div>
@endsection