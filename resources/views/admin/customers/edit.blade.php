@extends('layouts.dashboard')

@push('scripts')
<script src="{{ asset('js/modules/dynamic_product_search.js') }}"></script>
<script src="{{ asset('js/modules/customer_prices_manager.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        requireModule("CustomerPricesManager").init();

        @foreach($customer->customPrices as $customPrice)
            requireModule("CustomerPricesManager").restoreProduct(
                '{{ $customPrice->product->id }}',
                @json($customPrice->product->name),
                '{{ $customPrice->product->sku ?? "N/A" }}',
                '{{ $customPrice->product->price ?? ($customPrice->product->sale_price ?? 0) }}',
                '{{ $customPrice->price }}'
            );
        @endforeach
    });
</script>
@endpush

@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>Editar Cliente: {{ $customer->display_name }}</h2>
        <p>Actualiza la información del cliente, asigna listas de precios y configura precios especiales por producto.</p>
    </div>
    <div class="dashboard-header__actions">
        <x-bmdc-button type="link" href="{{ route('admin.customers.index') }}" variant="outlined">
            <x-slot name="mIcon">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>
            </x-slot>
            {{ __('messages.dashboard.stores.back')  }}
        </x-bmdc-button>
    </div>
</div>

<div class="form-panel" style="margin-bottom: 20px;">
    <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="customer_type">Tipo de Cliente *</label>
                    <select name="customer_type" id="customer_type" class="form-control @error('customer_type') is-invalid @enderror" required onchange="toggleCustomerFields(this.value)">
                        <option value="individual" {{ old('customer_type', $customer->customer_type) == 'individual' ? 'selected' : '' }}>Persona Física</option>
                        <option value="business" {{ old('customer_type', $customer->customer_type) == 'business' ? 'selected' : '' }}>Empresa / Negocio</option>
                    </select>
                    @error('customer_type') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="price_list_id">Lista de Precios Asignada</label>
                    <select name="price_list_id" id="price_list_id" class="form-control @error('price_list_id') is-invalid @enderror">
                        <option value="">-- General (Precios por defecto) --</option>
                        @foreach($priceLists as $list)
                            <option value="{{ $list->id }}" {{ old('price_list_id', $customer->price_list_id) == $list->id ? 'selected' : '' }}>{{ $list->name }}</option>
                        @endforeach
                    </select>
                    @error('price_list_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-row" id="individual_fields" style="display: {{ $customer->customer_type == 'individual' ? 'flex' : 'none' }};">
            <div class="form-col">
                <div class="form-group">
                    <label for="first_name">Nombre(s) *</label>
                    <input type="text" name="first_name" id="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $customer->first_name) }}">
                    @error('first_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="last_name">Apellidos *</label>
                    <input type="text" name="last_name" id="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $customer->last_name) }}">
                    @error('last_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-group" id="business_fields" style="display: {{ $customer->customer_type == 'business' ? 'block' : 'none' }};">
            <label for="business_name">Razón Social / Nombre del Negocio *</label>
            <input type="text" name="business_name" id="business_name" class="form-control @error('business_name') is-invalid @enderror" value="{{ old('business_name', $customer->business_name) }}">
            @error('business_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="phone">Teléfono</label>
                    <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $customer->phone) }}">
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $customer->email) }}">
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="tax_id">RFC / Tax ID</label>
                    <input type="text" name="tax_id" id="tax_id" class="form-control @error('tax_id') is-invalid @enderror" value="{{ old('tax_id', $customer->tax_id) }}" style="text-transform: uppercase;">
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col" style="padding-top: 10px;">
                <label style="display: block; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $customer->is_active) ? 'checked' : '' }}> Cliente Activo
                </label>
            </div>
        </div>

        <div class="form-group form-group-send" style="margin-top: 20px;">
            <x-bmdc-button type="submit" variant="raised">Actualizar Cliente</x-bmdc-button>
        </div>
    </form>
</div>

<!-- CUSTOM PRODUCT PRICES SECTION (Interactive Table using Dynamic Product Search) -->
<div class="form-panel">
    <h3>Precios Personalizados por Producto para este Cliente</h3>
    <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 15px;">Establece precios específicos que anularán la lista general cuando este cliente compre dichos productos.</p>

    <form action="{{ route('admin.customers.prices.store', $customer->id) }}" method="POST">
        @csrf

        <div class="form-group" style="margin-top: 15px;">
            <label>Seleccionar Producto para Agregar:</label>
            <x-dynamic-product-search 
                name="product_search" 
                :departments="$departments ?? []"
                :categories="$categories ?? []"
            />
            <small style="color: var(--text-muted);">Busca y selecciona un producto para agregarlo a la lista de precios personalizados.</small>
        </div>

        <div class="form-group">
            <label>Productos con Precio Especial:</label>
            <table class="data-table" id="selected-products-table" style="font-size: 0.9rem; margin-top: 5px;">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>SKU</th>
                        <th>Precio Base</th>
                        <th>Precio Especial ($)</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="no-products-row">
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 15px;">No se han seleccionado productos.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="form-group form-group-send" style="margin-top: 20px;">
            <x-bmdc-button type="submit" variant="raised">Guardar Precios Personalizados</x-bmdc-button>
        </div>
    </form>
</div> 

<script>
    function toggleCustomerFields(type) {
        var individual = document.getElementById('individual_fields');
        var business = document.getElementById('business_fields');
        if (type === 'individual') {
            individual.style.display = 'flex';
            business.style.display = 'none';
        } else {
            individual.style.display = 'none';
            business.style.display = 'block';
        }
    }
</script>
@endsection