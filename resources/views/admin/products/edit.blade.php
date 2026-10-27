@extends('layouts.dashboard')

@push('scripts')
<script src="{{ asset('js/modules/products_module.js') }}"></script>
<script src="{{ asset('js/modules/scanner_module.js') }}"></script>
<script src="{{ asset('js/modules/dynamic_product_search.js') }}"></script>

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        requireModule('ProductFormScanner').init('primary_barcode');
    });
</script>
@endpush

@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>{{ __('messages.dashboard.products.headline_edit') }}: {{ $product->name }}</h2>
    </div>
    <div class="dashboard-header__actions" style="display: flex; gap: 10px;">
        <x-bmdc-button type="link" href="{{ route('admin.products.index') }}" variant="outlined">
            <x-slot name="mIcon">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>
            </x-slot>
            {{ __('messages.dashboard.products.back') }}
        </x-bmdc-button>
        <form class="delete-form" action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline;" data-jscontroller="ProductsUIController" onsubmit="requireModule('ProductsUIController').confirmDeletion(event)">
            @csrf
            @method('DELETE')
            <x-bmdc-button type="submit" variant="unelevated">
                <x-slot name="mIcon">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M280-120q-33 0-56.5-23.5T200-200v-520q-17 0-28.5-11.5T160-760q0-17 11.5-28.5T200-800h160q0-17 11.5-28.5T400-840h160q17 0 28.5 11.5T600-800h160q17 0 28.5 11.5T800-760q0 17-11.5 28.5T760-720v520q0 33-23.5 56.5T680-120H280Zm148.5-171.5Q440-303 440-320v-280q0-17-11.5-28.5T400-640q-17 0-28.5 11.5T360-600v280q0 17 11.5 28.5T400-280q17 0 28.5-11.5Zm160 0Q600-303 600-320v-280q0-17-11.5-28.5T560-640q-17 0-28.5 11.5T520-600v280q0 17 11.5 28.5T560-280q17 0 28.5-11.5Z"/></svg>
                </x-slot>
                {{ __('messages.dashboard.products.delete') }}
            </x-bmdc-button>
        </form>
    </div>
</div>

<div class="form-panel">
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="sku">{{ __('messages.dashboard.products.form.sku') }} *</label>
                    <input type="text" name="sku" id="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku', $product->sku) }}" required autocomplete="off">
                    @error('sku') <span class="invalid-feedback" style="display: block; color: #dc2626; font-size: 0.85rem; margin-top: 4px;">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="name">{{ __('messages.dashboard.products.form.product_name') }} *</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required autocomplete="off">
                    @error('name') <span class="invalid-feedback" style="display: block; color: #dc2626; font-size: 0.85rem; margin-top: 4px;">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="department_id">{{ __('messages.dashboard.products.form.department') }} *</label>
                    <select name="department_id" id="department_id" class="form-control @error('department_id') is-invalid @enderror" required>
                        <option value="">-- {{ __('messages.dashboard.products.form.select_department') }} --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id', $product->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id') <span class="invalid-feedback" style="display: block; color: #dc2626; font-size: 0.85rem; margin-top: 4px;">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="category_id">{{ __('messages.dashboard.products.form.category') }}</label>
                    <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror">
                        <option value="">-- {{ __('messages.dashboard.products.form.select_category') }} --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="invalid-feedback" style="display: block; color: #dc2626; font-size: 0.85rem; margin-top: 4px;">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="product_type">{{ __('messages.dashboard.products.form.product_type') }} *</label>
                    <select name="product_type" id="product_type" class="form-control @error('product_type') is-invalid @enderror" required>
                        <option value="standard" {{ old('product_type', $product->product_type) == 'standard' ? 'selected' : '' }}>{{ __('messages.dashboard.products.form.product_type_standard') }}</option>
                        <option value="service" {{ old('product_type', $product->product_type) == 'service' ? 'selected' : '' }}>{{ __('messages.dashboard.products.form.product_type_service') }}</option>
                        <option value="kit" {{ old('product_type', $product->product_type) == 'kit' ? 'selected' : '' }}>{{ __('messages.dashboard.products.form.product_type_kit') }}</option>
                    </select>
                    @error('product_type') <span class="invalid-feedback" style="display: block; color: #dc2626; font-size: 0.85rem; margin-top: 4px;">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="sale_unit_id">{{ __('messages.dashboard.products.form.uom') }} *</label>
                    <select name="sale_unit_id" id="sale_unit_id" class="form-control @error('sale_unit_id') is-invalid @enderror" required>
                        <option value="">-- {{ __('messages.dashboard.products.form.select_uom') }} --</option>
                        @foreach($units as $u)
                            <option value="{{ $u->id }}" {{ old('sale_unit_id', $product->sale_unit_id) == $u->id ? 'selected' : '' }}>{{ ucwords(__("messages.measurement_units.{$u->code}.name")) }} ({{ __("messages.measurement_units.{$u->code}.symbol") }})</option>
                        @endforeach
                    </select>
                    @error('sale_unit_id') <span class="invalid-feedback" style="display: block; color: #dc2626; font-size: 0.85rem; margin-top: 4px;">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="inventory_unit_id">{{ __('messages.dashboard.products.form.uomi') }} *</label>
                    <select name="inventory_unit_id" id="inventory_unit_id" class="form-control @error('inventory_unit_id') is-invalid @enderror" required>
                        <option value="">-- {{ __('messages.dashboard.products.form.select_uom') }} --</option>
                        @foreach($units as $u)
                            <option value="{{ $u->id }}" {{ old('inventory_unit_id', $product->inventory_unit_id) == $u->id ? 'selected' : '' }}>{{ ucwords(__("messages.measurement_units.{$u->code}.name")) }} ({{ __("messages.measurement_units.{$u->code}.symbol") }})</option>
                        @endforeach
                    </select>
                    @error('inventory_unit_id') <span class="invalid-feedback" style="display: block; color: #dc2626; font-size: 0.85rem; margin-top: 4px;">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col" style="padding-top: 20px;">
                <label style="display: block; margin-bottom: 10px; cursor: pointer;">
                    <input type="checkbox" name="allows_fractional_qty" value="1" {{ old('allows_fractional_qty', $product->allows_fractional_qty) ? 'checked' : '' }}> {{ __('messages.dashboard.products.form.allow_fractional_qty') }}
                </label>
                <label style="display: block; margin-bottom: 10px; cursor: pointer;">
                    <input type="checkbox" name="track_inventory" value="1" {{ old('track_inventory', $product->track_inventory) ? 'checked' : '' }}> {{ __('messages.dashboard.products.form.track_inventory') }}
                </label>
                <label style="display: block; margin-bottom: 10px; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}> {{ __('messages.dashboard.products.form.product_is_active') }}
                </label>
            </div>
        </div>
        
        <div class="form-group form-group-send" style="margin-top: 15px; padding-top: 15px; border-top: 1px solid var(--border-color);">
            <x-bmdc-button type="submit" variant="raised">
                {{ __('messages.dashboard.products.form.update') }}
            </x-bmdc-button>
        </div>
    </form>
</div>

<!-- RELATED MODULE CARDS -->
<div class="cards-grid">
    
    <!-- CONDITIONAL BOX / PACKAGE CONFIGURATION CARD (Appears if sale unit is a box or package) -->
    @if($product->saleUnit && in_array(strtolower($product->saleUnit->code), ['box', 'package', 'paquete']))
    @php
        $unitCode = strtolower($product->saleUnit->code);
        $isPackage = in_array($unitCode, ['package', 'paquete']);
        $storeRouteName = $isPackage ? 'admin.products.package.storeComponent' : 'admin.products.box.components.store';
        $destroyRouteName = $isPackage ? 'admin.products.package.destroyComponent' : 'admin.products.box.components.destroy';
        $labelName = $isPackage ? 'paquete' : 'caja';
    @endphp

    <div class="module-card" style="grid-column: span 2;">
        <h3>Configuración de Contenido de {{ ucfirst($labelName) }}</h3>
        <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 15px;">Administre los productos individuales y cantidades contenidos en este {{ $labelName }}.</p>
        
        <!-- Add Component Form -->
        <form action="{{ route($storeRouteName, $product->id) }}" method="POST" style="margin-bottom: 15px;">
            @csrf
            <div style="margin-bottom: 12px;">
                <label for="component_product_id" style="font-size: 0.85rem; font-weight: 500;">Producto Contenido:</label>
                <div style="margin-top: 4px;">
                    <x-dynamic-product-search 
                        name="component_product_id" 
                        exclude-id="{{ $product->id }}" 
                        :departments="$departments"
                        :categories="$categories"
                    />
                </div>
                @error('component_product_id') <span class="invalid-feedback" style="display: block; color: #dc2626; font-size: 0.75rem; margin-top: 2px;">{{ $message }}</span> @enderror
            </div>
            
            <div style="display: flex; gap: 12px; align-items: end;">
                <div style="flex: 1">
                    <label for="component_qty" style="font-size: 0.85rem; font-weight: 500;">Cantidad:</label>
                    <input type="number" name="quantity" id="component_qty" class="form-control @error('quantity') is-invalid @enderror" step="any" min="0.0001" value="1" required style="margin-top: 4px;">
                    @error('quantity') <span class="invalid-feedback" style="display: block; color: #dc2626; font-size: 0.75rem; margin-top: 2px;">{{ $message }}</span> @enderror
                </div>
                <div>
                    <x-bmdc-button type="submit" variant="raised">Agregar</x-bmdc-button>
                </div>
            </div>

        </form>

        <!-- Components List Table -->
        <table class="data-table" style="font-size: 0.9rem;">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>SKU</th>
                    <th>Cantidad en {{ ucfirst($labelName) }}</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($product->components as $component)
                <tr>
                    <td><strong>{{ $component->name }}</strong></td>
                    <td>{{ $component->sku }}</td>
                    <td>{{ $component->pivot->quantity }}</td>
                    <td>
                        <form action="{{ route($destroyRouteName, [$product->id, $component->id]) }}" method="POST" onsubmit="return confirm('¿Remover este producto del {{ $labelName }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: red; cursor: pointer; font-weight: bold;">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #999; padding: 15px;">No hay productos configurados dentro de este {{ $labelName }}.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif

    <!-- 1. Product Images Card -->
    <div class="module-card">
        <h3>{{ __('messages.dashboard.products.cards.images.title') }}</h3>
        <p>{{ __('messages.dashboard.products.cards.images.description') }}</p>
        
        <!-- Upload Form -->
        <form action="{{ route('admin.products.images.store', $product->id) }}" method="POST" enctype="multipart/form-data" style="margin-bottom: 15px;">
            @csrf
            <div style="display: flex; gap: 10px; align-items: center;">
                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" required style="flex-grow: 1;">
                <button type="submit" class="btn btn-secondary">{{ __('messages.dashboard.products.cards.images.upload_button') }}</button>
            </div>
            @error('image') <span class="invalid-feedback" style="display: block; color: #dc2626; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</span> @enderror
        </form>

        <!-- Display Existing Images -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 15px;">
            @forelse($product->images as $image)
                <div style="position: relative; border: 1px solid #eee; border-radius: 4px; overflow: hidden; padding-bottom: 100%;">
                    <img src="{{ Storage::url($image->path . '_thumb.jpg') }}" alt="Product Image" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;">
                    
                    @if($image->is_primary)
                        <span style="position: absolute; top: 5px; left: 5px; background: var(--accent-color); color: white; padding: 2px 6px; border-radius: 4px; font-size: 0.7rem;">Cover</span>
                    @endif

                    <form action="{{ route('admin.products.images.destroy', [$product->id, $image->id]) }}" method="POST" style="position: absolute; top: 5px; right: 5px;" onsubmit="return confirm('Delete this image?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: red; color: white; border: none; border-radius: 50%; width: 24px; height: 24px; cursor: pointer;">&times;</button>
                    </form>
                </div>
            @empty
                <div style="grid-column: span 2; font-size: 0.9rem; color: #999; text-align: center; padding: 10px;">{{ __('messages.dashboard.products.cards.images.no_uploaded_images') }}</div>
            @endforelse
        </div>
    </div>

    <!-- 2. Barcodes Card -->
    <div class="module-card">
        <h3>{{ __('messages.dashboard.products.cards.barcodes.title') }}</h3>
        <p>{{ __('messages.dashboard.products.cards.barcodes.description') }}</p>
        
        <ul style="list-style: none; padding: 0; margin-bottom: 15px; flex-grow: 1;">
            @forelse($product->barcodes as $barcode)
                <li style="padding: 8px 0; border-bottom: 1px solid #eee; font-size: 0.9rem; display: flex; justify-content: space-between;">
                    <span style="font-family: monospace;">{{ $barcode->barcode }}</span>
                    @if($barcode->is_primary) <span style="background: var(--accent-color); color: white; padding: 2px 6px; border-radius: 4px; font-size: 0.7rem;">{{ __('messages.dashboard.products.cards.barcodes.primary') }}</span> @endif
                </li>
            @empty
                <li style="font-size: 0.9rem; color: #999;">{{ __('messages.dashboard.products.cards.barcodes.no_barcodes_assigned') }}</li>
            @endforelse
        </ul>
        <a href="{{ route('admin.products.barcodes.index', $product->id) }}" class="btn btn-secondary" style="text-align: center; margin-top: auto;">{{ __('messages.dashboard.products.cards.barcodes.manage_barcodes') }}</a>
    </div>

    <!-- 3. Pricing Card -->
    <div class="module-card">
        <h3>{{ __('messages.dashboard.products.cards.pricing.title') }}</h3>
        <p>{{ __('messages.dashboard.products.cards.pricing.description') }}</p>
        
        <div style="background: #f8f9fa; padding: 10px; border-radius: 4px; margin-bottom: 15px; text-align: center; flex-grow: 1;">
            <span style="font-size: 0.8rem; color: #666;">{{ __('messages.dashboard.products.cards.pricing.info') }}</span>
        </div>
        
        <a href="{{ route('admin.products.pricing.index', $product->id) }}" class="btn btn-secondary" style="text-align: center; margin-top: auto;">{{ __('messages.dashboard.products.cards.pricing.manage_pricing') }}</a>
    </div>

</div>
@endsection