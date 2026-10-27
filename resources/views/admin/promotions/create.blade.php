@extends('layouts.dashboard')

@push('scripts')
<script src="{{ asset('js/modules/dynamic_product_search.js') }}"></script>
<script src="{{ asset('js/modules/promotion_products_manager.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        requireModule("PromotionProductsManager").init();
        
        const typeSelect = document.getElementById('promotion_type');
        const promoPriceGroup = document.getElementById('promotional_price_group');
        const minQtyInput = document.getElementById('minimum_quantity');

        function updatePromotionUI() {
            const type = typeSelect.value;
            const selectedRows = document.querySelectorAll('#selected-products-table tbody tr:not(#no-products-row)');
            
            // Quantity rules adjustment
            if (type === 'single' || type === 'pack') {
                minQtyInput.removeAttribute('step');
                minQtyInput.removeAttribute('min');
                minQtyInput.setAttribute('step', '1');
                minQtyInput.setAttribute('min', '1');
                minQtyInput.value = Math.max(1, Math.round(minQtyInput.value || 1));
            } else {
                minQtyInput.setAttribute('step', '0.01');
                minQtyInput.setAttribute('min', '0.01');
            }

            // Global promotional price visibility: visible ONLY if type is 'single' and exactly 1 product is selected
            if (type === 'single' && selectedRows.length === 1) {
                promoPriceGroup.style.display = 'block';
            } else {
                promoPriceGroup.style.display = 'none';
                const promoPriceInput = document.getElementById('promotional_price');
                if (promoPriceInput) promoPriceInput.value = '';
            }
        }

        typeSelect.addEventListener('change', updatePromotionUI);
        
        // Expose update check globally or hook into manager events
        window.updatePromotionUI = updatePromotionUI;
        updatePromotionUI();
    });
</script>
@endpush

@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>Crear Promoción</h2>
        <p>Configura los detalles, tipo, vigencia, reglas de descuento y productos aplicables.</p>
    </div>
    <div class="dashboard-header__actions">
        <x-bmdc-button type="link" href="{{ route('admin.promotions.index') }}" variant="outlined">
            <x-slot name="mIcon">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>
            </x-slot>
            {{ __('messages.dashboard.stores.back')  }}
        </x-bmdc-button>
    </div>
</div>

<div class="form-panel">
    <form action="{{ route('admin.promotions.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="name">Nombre de la Promoción *</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autocomplete="off">
            @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="description">Descripción</label>
            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
            @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="banner_image">Banner Promocional (Imagen)</label>
            <input type="file" name="banner_image" id="banner_image" class="form-control @error('banner_image') is-invalid @enderror" accept="image/*">
            @error('banner_image') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="promotion_type">Tipo de Promoción *</label>
                    <select name="promotion_type" id="promotion_type" class="form-control @error('promotion_type') is-invalid @enderror" required>
                        <option value="single" {{ old('promotion_type') == 'single' ? 'selected' : '' }}>Individual</option>
                        <option value="pack" {{ old('promotion_type') == 'pack' ? 'selected' : '' }}>Paquete</option>
                        <option value="bulk" {{ old('promotion_type') == 'bulk' ? 'selected' : '' }}>Mayoreo / Bulto</option>
                    </select>
                    @error('promotion_type') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col" id="promotional_price_group">
                <div class="form-group">
                    <label for="promotional_price">Precio Promocional Global ($) *</label>
                    <input type="number" step="0.01" min="0" name="promotional_price" id="promotional_price" class="form-control @error('promotional_price') is-invalid @enderror" value="{{ old('promotional_price', 0) }}" autocomplete="off">
                    @error('promotional_price') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="minimum_quantity">Cantidad Mínima Requerida *</label>
                    <input type="number" name="minimum_quantity" id="minimum_quantity" class="form-control @error('minimum_quantity') is-invalid @enderror" value="{{ old('minimum_quantity', 1) }}" required autocomplete="off">
                    @error('minimum_quantity') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- PRICING & RULE CONFIGURATION MODULE -->
        <div class="form-row" style="background: #f8f9fa; padding: 15px; border-radius: 6px; border: 1px solid var(--border-color); margin-top: 15px; margin-bottom: 15px;">
            <div class="form-col">
                <div class="form-group">
                    <label>Tipo de Regla / Descuento</label>
                    <select name="rule_type" class="form-control">
                        <option value="fixed_price">Precio Fijo</option>
                        <option value="unit_discount">Descuento por Unidad</option>
                        <option value="percentage">Porcentaje de Descuento (%)</option>
                    </select>
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label>Descuento / Valor del Precio ($ o %)</label>
                    <input type="number" step="0.01" min="0" name="discount_value" class="form-control" value="{{ old('discount_value', 0) }}" autocomplete="off">
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="starts_at">Inicia (Opcional)</label>
                    <input type="datetime-local" name="starts_at" id="starts_at" class="form-control @error('starts_at') is-invalid @enderror" value="{{ old('starts_at') }}">
                    @error('starts_at') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="ends_at">Finaliza (Opcional)</label>
                    <input type="datetime-local" name="ends_at" id="ends_at" class="form-control @error('ends_at') is-invalid @enderror" value="{{ old('ends_at') }}">
                    @error('ends_at') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Dynamic Product Search Component Integration -->
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

        <!-- Selected Products Table Container with Base Price & Pivot Overrides -->
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
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}> Promoción Activa
                </label>
            </div>
        </div>

        <div class="form-group form-group-send" style="margin-top: 20px;">
            <x-bmdc-button type="submit" variant="raised">
                Guardar Promoción
            </x-bmdc-button>
        </div>
    </form>
</div>
@endsection