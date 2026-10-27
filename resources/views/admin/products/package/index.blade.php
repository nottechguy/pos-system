@extends('layouts.dashboard')

@push('scripts')
<script src="{{ asset('js/modules/dynamic_product_search.js') }}"></script>
@endpush

@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>Configuración de Paquete: {{ $product->name }}</h2>
        <p>Indique qué producto conforma este paquete y cuántas unidades contiene.</p>
    </div>
</div>

<div class="form-panel" style="max-width: 700px;">
    <form action="{{ route('admin.products.package.store', $product->id) }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="component_product_id">Producto Unitario Contenido *</label>
            @php
                $existingComponent = $product->components->first();
            @endphp
            <x-dynamic-product-search 
                name="component_product_id" 
                exclude-id="{{ $product->id }}" 
                initial-value="{{ old('component_product_id', $existingComponent?->id) }}"
                initial-text="{{ $existingComponent ? $existingComponent->name . ' (SKU: ' . $existingComponent->sku . ')' : '' }}"
                :departments="$departments"
                :categories="$categories"
            />
            @error('component_product_id') 
                <span class="invalid-feedback" style="display: block; color: #dc2626; font-size: 0.85rem; margin-top: 4px;">{{ $message }}</span> 
            @enderror
        </div>

        <div class="form-group">
            <label for="quantity">Cantidad de unidades en el paquete *</label>
            <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" step="any" min="0.0001" value="{{ old('quantity', $existingComponent?->pivot->quantity ?? 1) }}" required>
            @error('quantity') 
                <span class="invalid-feedback" style="display: block; color: #dc2626; font-size: 0.85rem; margin-top: 4px;">{{ $message }}</span> 
            @enderror
        </div>

        <div class="form-group form-group-send" style="margin-top: 20px;">
            <x-bmdc-button type="submit" variant="raised">Guardar y Continuar a Precios</x-bmdc-button>
        </div>
    </form>
</div>
@endsection