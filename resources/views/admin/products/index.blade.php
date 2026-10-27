@extends('layouts.dashboard')

@push('scripts')
<script src="{{ asset('js/modules/products_module.js') }}"></script>
<script src="{{ asset('js/modules/scanner_module.js') }}"></script>

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        var token = document.getElementById('csrf_token').content;
        requireModule('ProductAsyncSearch').init("{{ route('admin.products.scan_search') }}", token);
    });

    function toggleFilterPanel() {
        var panel = document.getElementById('product_filter_panel');
        panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    }
</script>
@endpush

@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>{{ __('messages.dashboard.products.headline') }}</h2>
        <p>{{ __('messages.dashboard.products.description') }}</p>
    </div>
    <div class="dashboard-header__actions">
        <x-bmdc-button type="link" href="{{ route('admin.products.create') }}" variant="unelevated">
                <x-slot name="mIcon">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#ffffff"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                </x-slot>
                {{ __('messages.dashboard.products.new_product_button') }}
        </x-bmdc-button>
    </div>
</div>

<!-- Search & Filter Trigger Bar -->
<div class="form-panel" style="padding: 15px; margin-bottom: 15px;">
    <form action="{{ route('admin.products.index') }}" method="GET">
        <div style="display: flex; gap: 10px; margin-bottom: 10px;">
            <input type="text" name="search" class="form-control" placeholder="{{ __('messages.dashboard.products.search_placeholder') }}" value="{{ request('search') }}" autofocus autocomplete="off" style="flex: 1;">
            
            <button type="button" class="btn btn-secondary" onclick="toggleFilterPanel()" style="display: flex; align-items: center; gap: 5px;">
                <span>⚙️ Filtros</span>
            </button>

            <button type="submit" class="btn btn-secondary" style="text-transform: capitalize;">{{ __('messages.dashboard.products.search_button') }}</button>
            
            @if(request('search') || request('filters') || request('department_id'))
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary" style="background-color: #e0e0e0; color: #333;">Limpiar</a>
            @endif
        </div>

        <!-- Collapsible Attribute Filter Panel -->
        <div id="product_filter_panel" style="display: {{ request('filters') ? 'block' : 'none' }}; background: #f9fafb; padding: 15px; border-radius: 8px; border: 1px solid #e5e7eb; margin-top: 10px;">
            <h4 style="margin-top: 0; margin-bottom: 10px; font-size: 0.95rem; color: #374151;">Filtrar por Atributos</h4>
            
            <div style="display: flex; flex-wrap: wrap; gap: 20px;">
                <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 0.9rem;">
                    <input type="checkbox" name="filters[is_active]" value="1" {{ request('filters.is_active') ? 'checked' : '' }}>
                    <span>Activo</span>
                </label>

                <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 0.9rem;">
                    <input type="checkbox" name="filters[allows_fractional_qty]" value="1" {{ request('filters.allows_fractional_qty') ? 'checked' : '' }}>
                    <span>Permite Fracción (Granel/Peso)</span>
                </label>

                <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 0.9rem;">
                    <input type="checkbox" name="filters[track_inventory]" value="1" {{ request('filters.track_inventory') ? 'checked' : '' }}>
                    <span>Controla Inventario</span>
                </label>

                <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 0.9rem;">
                    <input type="checkbox" name="filters[allow_negative_inventory]" value="1" {{ request('filters.allow_negative_inventory') ? 'checked' : '' }}>
                    <span>Permite Inventario Negativo</span>
                </label>
            </div>

            <!-- Optional Department Quick Select -->
            <div style="margin-top: 12px; display: flex; align-items: center; gap: 10px;">
                <label for="department_filter" style="font-size: 0.85rem; font-weight: 500; color: #4b5563;">Departamento:</label>
                <select name="department_id" id="department_filter" class="form-control" style="max-width: 250px; padding: 4px 8px; font-size: 0.9rem;">
                    <option value="">-- Todos los departamentos --</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>Precio (Público)</th> <!-- Replaced SKU with Price by default -->
            <th>{{ __('messages.dashboard.products.table.name') }}</th>
            <th>{{ __('messages.dashboard.products.table.department') }}</th>
            <th style="text-overflow: ellipsis; max-width: 140px">{{ __('messages.dashboard.products.table.uom') }}</th>
            <th>{{ __('messages.dashboard.products.table.status') }}</th>
            <th>{{ __('messages.dashboard.products.table.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse($products as $product)
        <tr>
            <td><strong style="color: green;">${{ number_format($product->sale_price, 2) }}</strong></td>
            <td><strong>{{ $product->name }}</strong></td>
            <td>{{ $product->department ? $product->department->name : '-' }}</td>
            <td>{{ $product->saleUnit ? ucwords(__("messages.measurement_units.{$product->saleUnit->code}.name")) : '-' }}</td>
            <td>
                <span style="color: {{ $product->is_active ? 'green' : 'red' }}; font-weight: bold;">
                    {{ $product->is_active ? __('messages.dashboard.products.status_active') : __('messages.dashboard.products.status_inactive') }}
                </span>
            </td>
            <td class="action-links" data-jscontroller="ProductsUIController">
                <a href="{{ route('admin.products.edit', $product->id) }}">
                    <x-table-action-button type="button" class="" action="edit">{{ __('messages.edit') }}</x-table-action-button>
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align: center; padding: 20px;">{{ __('messages.dashboard.products.no_products_found') }}</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top: 20px;">
    {{ $products->links('partials.custom-pagination') }}
</div>

@endsection