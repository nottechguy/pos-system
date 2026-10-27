@extends('layouts.dashboard')

@push('scripts')
<script type="text/javascript">
    function toggleFilterPanel() {
        var panel = document.getElementById('sale_filter_panel');
        panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    }
</script>
@endpush

@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>Historial de Ventas</h2>
        <p>Consulte y reimprima los tickets de las ventas completadas.</p>
    </div>
</div>

<!-- Search & Filter Trigger Bar -->
<div class="form-panel" style="padding: 15px; margin-bottom: 15px;">
    <form action="{{ route('admin.sales.index') }}" method="GET">
        <div style="display: flex; gap: 10px; margin-bottom: 10px;">
            <input type="text" name="search" class="form-control" placeholder="Buscar por número de ticket..." value="{{ request('search') }}" autofocus autocomplete="off" style="flex: 1;">
            
            <button type="button" class="btn btn-secondary" onclick="toggleFilterPanel()" style="display: flex; align-items: center; gap: 5px;">
                <span>⚙️ Filtros de Fecha</span>
            </button>

            <button type="submit" class="btn btn-secondary">Buscar</button>
            
            @if(request('search') || request('date_from') || request('date_to'))
                <a href="{{ route('admin.sales.index') }}" class="btn btn-secondary" style="background-color: #e0e0e0; color: #333;">Limpiar</a>
            @endif
        </div>

        <!-- Collapsible Attribute Filter Panel -->
        <div id="sale_filter_panel" style="display: {{ request('date_from') || request('date_to') ? 'block' : 'none' }}; background: #f9fafb; padding: 15px; border-radius: 8px; border: 1px solid #e5e7eb; margin-top: 10px;">
            <h4 style="margin-top: 0; margin-bottom: 10px; font-size: 0.95rem; color: #374151;">Filtrar por Rango de Fechas</h4>
            
            <div style="display: flex; flex-wrap: wrap; gap: 20px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <label for="date_from" style="font-size: 0.85rem; font-weight: 500; color: #4b5563;">Desde:</label>
                    <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <label for="date_to" style="font-size: 0.85rem; font-weight: 500; color: #4b5563;">Hasta:</label>
                    <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
            </div>
        </div>
    </form>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>Ticket #</th>
            <th>Fecha</th>
            <th>Cajero</th>
            <th>Monto Total</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($sales as $sale)
        <tr>
            <td><strong>{{ $sale->number }}</strong></td>
            <td>{{ $sale->sold_at->format('d/m/Y h:i A') }}</td>
            <td>{{ $sale->cashier ? $sale->cashier->first_name . ' ' . $sale->cashier->last_name : 'N/A' }}</td>
            <td><strong style="color: green;">${{ number_format($sale->total, 2) }}</strong></td>
            <td>
                <span style="color: {{ $sale->status === 'completed' ? 'green' : 'red' }}; font-weight: bold;">
                    {{ ucfirst($sale->status) }}
                </span>
            </td>
            <td class="action-links">
                <a href="{{ route('admin.sales.show', $sale->id) }}">
                    <x-table-action-button type="button" action="view">Ver / Reimprimir</x-table-action-button>
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align: center; padding: 20px;">No se encontraron ventas.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top: 20px;">
    {{ $sales->links('partials.custom-pagination') }}
</div>
@endsection