@extends('layouts.dashboard')

@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>Control de Inventario</h2>
        <p>Monitoreo de existencias y balances actuales por sucursal.</p>
    </div>
</div>

<div class="form-panel" style="padding: 15px; margin-bottom: 15px;">
    <form action="{{ route('admin.inventory.index') }}" method="GET" style="display: flex; gap: 10px; flex-wrap: wrap;">
        <input type="text" name="search" class="form-control" placeholder="Buscar por nombre o SKU..." value="{{ request('search') }}" style="flex: 1; min-width: 250px;">
        
        <select name="store_id" class="form-control" style="max-width: 200px;">
            @foreach($stores as $store)
                <option value="{{ $store->id }}" {{ $storeId == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
            @endforeach
        </select>

        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; padding: 0 10px;">
            <input type="checkbox" name="low_stock" value="1" {{ request('low_stock') ? 'checked' : '' }}>
            <span>Stock Bajo</span>
        </label>

        <button type="submit" class="btn btn-secondary">Filtrar</button>
        <a href="{{ route('admin.inventory.movements') }}" class="btn btn-secondary" style="background: #e0e0e0; color: #333;">Ver Movimientos</a>
    </form>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>SKU</th>
            <th>Producto</th>
            <th>En Stock (Qoh)</th>
            <th>Reservado</th>
            <th>Disponible</th>
            <th>Unidad</th>
        </tr>
    </thead>
    <tbody>
        @forelse($balances as $balance)
        <tr>
            <td>{{ $balance->product->sku }}</td>
            <td><strong>{{ $balance->product->name }}</strong></td>
            <td>{{ number_format($balance->quantity_on_hand, 3) }}</td>
            <td>{{ number_format($balance->quantity_reserved, 3) }}</td>
            <td><strong style="color: {{ $balance->quantity_available > 0 ? 'green' : 'red' }}">{{ number_format($balance->quantity_available, 3) }}</strong></td>
            <td>{{ $balance->product->saleUnit ? __("messages.measurement_units.{$balance->product->saleUnit->code}.symbol") : '' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align: center; padding: 20px;">No se encontraron registros de inventario.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top: 20px;">
    {{ $balances->links('partials.custom-pagination') }}
</div>
@endsection