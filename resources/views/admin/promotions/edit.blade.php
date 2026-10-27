@extends('layouts.dashboard')

@push('scripts')
<script src="{{ asset('js/modules/dynamic_product_search.js') }}"></script>
<script src="{{ asset('js/modules/promotion_products_manager.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        requireModule("PromotionProductsManager").init();

        @foreach($promotion->products as $product)
            requireModule("PromotionProductsManager").restoreProduct(
                '{{ $product->id }}',
                @json($product->name),
                '{{ $product->sku ?? "N/A" }}',
                '{{ $product->price ?? ($product->sale_price ?? 0) }}',
                '{{ $product->pivot->promotional_price ?? "" }}',
                '{{ $product->pivot->discount_value ?? "" }}'
            );
        @endforeach

        const typeSelect = document.getElementById('promotion_type');
        const promoPriceGroup = document.getElementById('promotional_price_group');
        const minQtyInput = document.getElementById('minimum_quantity');

        function updatePromotionUI() {
            const type = typeSelect.value;
            const selectedRows = document.querySelectorAll('#selected-products-table tbody tr:not(#no-products-row)');
            
            if (type === 'single' || type === 'pack') {
                minQtyInput.removeAttribute('step');
                minQtyInput.removeAttribute('min');
                minQtyInput.setAttribute('step', '1');
                minQtyInput.setAttribute('min', '1');
            } else {
                minQtyInput.setAttribute('step', '0.01');
                minQtyInput.setAttribute('min', '0.01');
            }

            if (type === 'single' && selectedRows.length === 1) {
                promoPriceGroup.style.display = 'block';
            } else {
                promoPriceGroup.style.display = 'none';
            }
        }

        typeSelect.addEventListener('change', updatePromotionUI);
        window.updatePromotionUI = updatePromotionUI;
        updatePromotionUI();
    });
</script>
@endpush

@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>Editar Promoción</h2>
        <p>Modifica los detalles, vigencia, banner, reglas de descuento y productos aplicables.</p>
    </div>
    <div class="dashboard-header__actions">
        <x-bmdc-button type="link" href="{{ route('admin.products.index') }}" variant="outlined">
            <x-slot name="mIcon">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>
            </x-slot>
            {{ __('messages.dashboard.promotions.back') }}
        </x-bmdc-button>
        <form class="delete-form" action="{{ route('admin.promotions.destroy', $promotion->id) }}" method="POST" style="display:inline;" data-jscontroller="ProductsUIController" onsubmit="requireModule('ProductsUIController').confirmDeletion(event)">
            @csrf
            @method('DELETE')
            <x-bmdc-button type="submit" variant="unelevated">
                <x-slot name="mIcon">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M280-120q-33 0-56.5-23.5T200-200v-520q-17 0-28.5-11.5T160-760q0-17 11.5-28.5T200-800h160q0-17 11.5-28.5T400-840h160q17 0 28.5 11.5T600-800h160q17 0 28.5 11.5T800-760q0 17-11.5 28.5T760-720v520q0 33-23.5 56.5T680-120H280Zm148.5-171.5Q440-303 440-320v-280q0-17-11.5-28.5T400-640q-17 0-28.5 11.5T360-600v280q0 17 11.5 28.5T400-280q17 0 28.5-11.5Zm160 0Q600-303 600-320v-280q0-17-11.5-28.5T560-640q-17 0-28.5 11.5T520-600v280q0 17 11.5 28.5T560-280q17 0 28.5-11.5Z"/></svg>
                </x-slot>
                {{ __('messages.dashboard.promotions.delete') }}
            </x-bmdc-button>
        </form>
    </div>
</div>

<div class="form-panel">
    <form action="{{ route('admin.promotions.update', $promotion->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nombre de la Promoción *</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $promotion->name) }}" required autocomplete="off">
            @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="description">Descripción</label>
            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $promotion->description) }}</textarea>
            @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="banner_image">Banner Promocional (Imagen)</label>
            @if($promotion->banner_image)
                <div style="margin-bottom: 8px;">
                    <img src="{{ asset('storage/' . $promotion->banner_image) }}" alt="Banner actual" style="max-height: 80px; border-radius: 4px; border: 1px solid #ddd;">
                    <small style="display: block; color: var(--text-muted);">Banner actual (subir uno nuevo reemplazará este).</small>
                </div>
            @endif
            <input type="file" name="banner_image" id="banner_image" class="form-control @error('banner_image') is-invalid @enderror" accept="image/*">
            @error('banner_image') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="promotion_type">Tipo de Promoción *</label>
                    <select name="promotion_type" id="promotion_type" class="form-control @error('promotion_type') is-invalid @enderror" required>
                        <option value="single" {{ old('promotion_type', $promotion->promotion_type) == 'single' ? 'selected' : '' }}>Individual (Single)</option>
                        <option value="pack" {{ old('promotion_type', $promotion->promotion_type) == 'pack' ? 'selected' : '' }}>Paquete (Pack)</option>
                        <option value="bulk" {{ old('promotion_type', $promotion->promotion_type) == 'bulk' ? 'selected' : '' }}>Mayoreo / Bulto (Bulk)</option>
                    </select>
                    @error('promotion_type') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col" id="promotional_price_group">
                <div class="form-group">
                    <label for="promotional_price">Precio Promocional Global ($) *</label>
                    <input type="number" step="0.01" min="0" name="promotional_price" id="promotional_price" class="form-control @error('promotional_price') is-invalid @enderror" value="{{ old('promotional_price', $promotion->promotional_price) }}" autocomplete="off">
                    @error('promotional_price') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="minimum_quantity">Cantidad Mínima Requerida *</label>
                    <input type="number" name="minimum_quantity" id="minimum_quantity" class="form-control @error('minimum_quantity') is-invalid @enderror" value="{{ old('minimum_quantity', $promotion->minimum_quantity) }}" required autocomplete="off">
                    @error('minimum_quantity') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Dynamic Product Search Component -->
        <div class="form-group" style="margin-top: 15px;">
            <label>Agregar Productos Aplicables a la Promoción *</label>
            <x-dynamic-product-search 
                name="product_search" 
                :departments="$departments ?? []"
                :categories="$categories ?? []"
            />
            <small style="color: var(--text-muted);">Busca y selecciona los productos que formarán parte de esta promoción.</small>
            @error('products') <span class="invalid-feedback" style="display:block;">{{ $message }}</span> @enderror
        </div>

        <!-- Selected Products Table Container -->
        <div class="form-group">
            <label>Productos Seleccionados:</label>
            <table class="data-table" id="selected-products-table" style="font-size: 0.9rem; margin-top: 5px;">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>SKU</th>
                        <th>Precio Base</th>
                        <th>Precio Promocional</th>
                        <th>Descuento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="no-products-row">
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 15px;">No se han seleccionado productos.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="form-row">
            <div class="form-col" style="padding-top: 10px;">
                <label style="display: block; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $promotion->is_active) ? 'checked' : '' }}> Promoción Activa
                </label>
            </div>
        </div>

        <div class="form-group form-group-send" style="margin-top: 20px;">
            <x-bmdc-button type="submit" variant="raised">
                Actualizar Promoción
            </x-bmdc-button>
        </div>
    </form>
</div>
@endsection