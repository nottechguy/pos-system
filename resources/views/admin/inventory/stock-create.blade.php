@extends('layouts.dashboard')

@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>Inventario Inicial: {{ $product->name }}</h2>
        <p>Establezca la cantidad inicial disponible en almacén para este producto.</p>
    </div>
</div>

<div class="form-panel" style="max-width: 600px;">
    <form action="{{ route('admin.inventory.stock.store', $product->id) }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="store_id">Sucursal / Almacén *</label>
            <select name="store_id" id="store_id" class="form-control @error('store_id') is-invalid @enderror" required>
                @foreach($stores as $store)
                    <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                @endforeach
            </select>
            @error('store_id') 
                <span class="invalid-feedback" style="display: block; color: #dc2626; font-size: 0.85rem; margin-top: 4px;">{{ $message }}</span> 
            @enderror
        </div>

        <div class="form-group">
            <label for="quantity">Cantidad Inicial en Stock *</label>
            <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" step="any" min="0" value="{{ old('quantity', 0) }}" required>
            @error('quantity') 
                <span class="invalid-feedback" style="display: block; color: #dc2626; font-size: 0.85rem; margin-top: 4px;">{{ $message }}</span> 
            @enderror
        </div>

        <div class="form-group">
            <label for="reason">Motivo / Notas</label>
            <input type="text" name="reason" id="reason" class="form-control @error('reason') is-invalid @enderror" value="{{ old('reason', 'Carga inicial de inventario') }}">
            @error('reason') 
                <span class="invalid-feedback" style="display: block; color: #dc2626; font-size: 0.85rem; margin-top: 4px;">{{ $message }}</span> 
            @enderror
        </div>

        <div class="form-group form-group-send" style="margin-top: 20px;">
            <x-bmdc-button type="submit" variant="raised">Finalizar Alta de Producto</x-bmdc-button>
        </div>
    </form>
</div>
@endsection