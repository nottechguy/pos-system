@extends('layouts.dashboard')

@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>Estadísticas y Utilidades de Ventas</h2>
        <p>Análisis detallado de ingresos reales, costos de inventario (COGS), márgenes y métodos de pago.</p>
    </div>
    <div class="dashboard-header__actions">
        <x-bmdc-button type="link" href="{{ route('admin.sales.index') }}" variant="outlined">
            <x-slot name="mIcon">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>
            </x-slot>
            Volver a Ventas
        </x-bmdc-button>
    </div>
</div>

<!-- Filter Panel -->
<div class="form-panel" style="padding: 15px; margin-bottom: 20px;">
    <form action="{{ route('admin.sales.statistics') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
        <div style="flex: 1; min-width: 150px;">
            <label for="period" style="font-size: 0.85rem; font-weight: 500; display: block; margin-bottom: 5px;">Período Rápido:</label>
            <select name="period" id="period" class="form-control" onchange="this.form.submit()">
                <option value="all" {{ ($filters['period'] ?? '') == 'all' ? 'selected' : '' }}>Todo el Histórico</option>
                <option value="day" {{ ($filters['period'] ?? '') == 'day' ? 'selected' : '' }}>Hoy</option>
                <option value="week" {{ ($filters['period'] ?? '') == 'week' ? 'selected' : '' }}>Esta Semana</option>
                <option value="month" {{ ($filters['period'] ?? '') == 'month' ? 'selected' : '' }}>Este Mes</option>
                <option value="year" {{ ($filters['period'] ?? '') == 'year' ? 'selected' : '' }}>Este Año</option>
            </select>
        </div>

        <div style="flex: 1; min-width: 150px;">
            <label for="date_from" style="font-size: 0.85rem; font-weight: 500; display: block; margin-bottom: 5px;">Desde:</label>
            <input type="date" name="date_from" id="date_from" class="form-control" value="{{ $filters['date_from'] ?? '' }}">
        </div>

        <div style="flex: 1; min-width: 150px;">
            <label for="date_to" style="font-size: 0.85rem; font-weight: 500; display: block; margin-bottom: 5px;">Hasta:</label>
            <input type="date" name="date_to" id="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}">
        </div>

        <div style="flex: 1; min-width: 180px;">
            <label for="cashier_id" style="font-size: 0.85rem; font-weight: 500; display: block; margin-bottom: 5px;">Cajero:</label>
            <select name="cashier_id" id="cashier_id" class="form-control">
                <option value="">-- Todos los cajeros --</option>
                @foreach($cashiers as $cashier)
                    <option value="{{ $cashier->id }}" {{ ($filters['cashier_id'] ?? '') == $cashier->id ? 'selected' : '' }}>{{ $cashier->full_name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <button type="submit" class="btn btn-secondary" style="height: 38px; padding: 0 20px;">Filtrar</button>
            <a href="{{ route('admin.sales.statistics') }}" class="btn btn-secondary" style="height: 38px; line-height: 38px; background: #e0e0e0; color: #333; text-decoration: none; display: inline-block; padding: 0 15px; margin-left: 5px;">Limpiar</a>
        </div>
    </form>
</div>

<!-- KPI Cards Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px; margin-bottom: 25px;">
    <div class="form-panel" style="padding: 20px; border-left: 4px solid #2563eb;">
        <div style="font-size: 0.85rem; color: #4b5563;">Ventas Totales (Bruto)</div>
        <div style="font-size: 1.6rem; font-weight: bold; color: #1f2937; margin-top: 5px;">${{ number_format($stats['gross_revenue'], 2) }}</div>
        <div style="font-size: 0.8rem; color: #6b7280; margin-top: 2px;">{{ $stats['total_sales_count'] }} transacciones completadas</div>
    </div>

    <div class="form-panel" style="padding: 20px; border-left: 4px solid #dc2626;">
        <div style="font-size: 0.85rem; color: #4b5563;">Costo de Inventario (COGS)</div>
        <div style="font-size: 1.6rem; font-weight: bold; color: #dc2626; margin-top: 5px;">${{ number_format($stats['total_cost'], 2) }}</div>
        <div style="font-size: 0.8rem; color: #6b7280; margin-top: 2px;">Basado en costos de productos</div>
    </div>

    <div class="form-panel" style="padding: 20px; border-left: 4px solid #059669;">
        <div style="font-size: 0.85rem; color: #4b5563;">Utilidad Neta Real</div>
        <div style="font-size: 1.6rem; font-weight: bold; color: #059669; margin-top: 5px;">${{ number_format($stats['net_profit'], 2) }}</div>
        <div style="font-size: 0.8rem; color: #6b7280; margin-top: 2px;">Margen: {{ number_format($stats['profit_margin'], 1) }}%</div>
    </div>

    <div class="form-panel" style="padding: 20px; border-left: 4px solid #d97706;">
        <div style="font-size: 0.85rem; color: #4b5563;">Descuentos Otorgados</div>
        <div style="font-size: 1.6rem; font-weight: bold; color: #d97706; margin-top: 5px;">${{ number_format($stats['total_discount'], 2) }}</div>
    </div>
</div>

<!-- Detailed Breakdowns -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
    <!-- Top Sold Products -->
    <div class="form-panel" style="padding: 20px;">
        <h3 style="margin-top: 0; margin-bottom: 15px; font-size: 1.1rem;">Top 5 Productos Más Vendidos</h3>
        <table class="data-table" style="width: 100%;">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th style="text-align: center;">Cantidad Vendida</th>
                    <th style="text-align: right;">Ingreso Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stats['top_products'] as $product)
                <tr>
                    <td><strong>{{ $product['name'] }}</strong></td>
                    <td style="text-align: center;">{{ $product['quantity'] }}</td>
                    <td style="text-align: right; color: #059669;"><strong>${{ number_format($product['revenue'], 2) }}</strong></td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center; padding: 20px;">No hay registros de ventas para el período seleccionado.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Payment Methods Breakdown -->
    <div class="form-panel" style="padding: 20px;">
        <h3 style="margin-top: 0; margin-bottom: 15px; font-size: 1.1rem;">Ingresos por Método de Pago</h3>
        <table class="data-table" style="width: 100%;">
            <thead>
                <tr>
                    <th>Método</th>
                    <th style="text-align: right;">Total Recaudado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stats['payment_breakdown'] as $payment)
                <tr>
                    <td>{{ __('payment_methods.' . $payment->code . '.name') }}</td>
                    <td style="text-align: right;"><strong>${{ number_format($payment->total, 2) }}</strong></td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" style="text-align: center; padding: 20px;">Sin datos.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection