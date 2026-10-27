@extends('layouts.dashboard')

@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>Detalle de Venta: {{ $sale->number }}</h2>
        <p>Fecha: {{ $sale->sold_at->format('d/m/Y h:i A') }}</p>
    </div>
    <div class="dashboard-header__actions" style="display: flex; gap: 10px;">
        <x-bmdc-button type="link" href="{{ route('admin.sales.index') }}" variant="outlined">
            <x-slot name="mIcon">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>
            </x-slot>
            Volver a Ventas
        </x-bmdc-button>

        <form action="{{ route('admin.sales.print', $sale->id) }}" method="POST" style="display:inline;">
            @csrf
            <input type="hidden" name="mode" value="Ticket">
            <x-bmdc-button type="submit" variant="raised">
                <x-slot name="mIcon">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M640-640v-120H320v120h-80v-200h480v200h-80Zm-480 80h640-640Zm560 100q17 0 28.5-11.5T760-500q0-17-11.5-28.5T720-540q-17 0-28.5 11.5T680-500q0 17 11.5 28.5T720-460Zm-80 260v-160H320v160h320Zm80 80H240v-160H80v-240q0-51 35-85.5t85-34.5h560q51 0 85 34.5t35 85.5v240H720v160Zm80-240v-160q0-17-11.5-28.5T760-600H200q-17 0-28.5 11.5T160-560v160h80v-80h480v80h80Z"/></svg>
                </x-slot>
                Reimprimir Ticket
            </x-bmdc-button>
        </form>
    </div>
</div>

<div class="cards-grid">
    <!-- Left Column: Line Items -->
    <div class="module-card" style="grid-column: span 2;">
        <h3>Artículos Vendidos</h3>
        <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 15px;">Información preservada en el momento de la venta.</p>
        
        <table class="data-table" style="font-size: 0.9rem;">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cant.</th>
                    <th>Unidad</th>
                    <th>Precio Unit.</th>
                    <th>Descuento</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->name_snapshot }}</strong><br>
                        <small style="color: #666;">SKU: {{ $item->sku_snapshot }}</small>
                    </td>
                    <td>{{ (float) $item->quantity }}</td>
                    <td>{{ $item->unit?->code ? ucwords(__("messages.measurement_units.{$item->unit?->code}.name")) : '—' }}</td>
                    <td>${{ number_format($item->unit_price, 2) }}</td>
                    <td style="color: red;">
                        {{ $item->discount_total > 0 ? '-$' . number_format($item->discount_total, 2) : '$0.00' }}
                    </td>
                    <td><strong>${{ number_format($item->line_total, 2) }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Right Column: Sale Summary -->
    <div class="module-card">
        <h3>Resumen de Venta</h3>
        <ul style="list-style: none; padding: 0; margin: 0;">
            <li style="padding: 10px 0; border-bottom: 1px solid #eee; display: flex; justify-content: space-between;">
                <span>Subtotal:</span>
                <span>${{ number_format($sale->subtotal, 2) }}</span>
            </li>
            <li style="padding: 10px 0; border-bottom: 1px solid #eee; display: flex; justify-content: space-between;">
                <span>Descuentos:</span>
                <span style="color: red;">-${{ number_format($sale->discount_total, 2) }}</span>
            </li>
            <li style="padding: 10px 0; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; font-weight: bold; font-size: 1.1rem;">
                <span>Total:</span>
                <span style="color: green;">${{ number_format($sale->total, 2) }}</span>
            </li>
        </ul>
        
        <h4 style="margin-top: 20px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">Pagos Recibidos</h4>
        <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.9rem;">
            @foreach($sale->payments as $payment)
            <li style="padding: 5px 0; display: flex; justify-content: space-between;">
                <span>{{ $payment->paymentMethod ? __("payment_methods." . $payment->paymentMethod->code . ".name") : 'N/A' }}</span>
                <span>${{ number_format($payment->amount, 2) }}</span>
            </li>
            @endforeach
            <li style="padding: 10px 0 0; display: flex; justify-content: space-between; font-weight: bold; border-top: 1px solid #eee; margin-top: 5px;">
                <span>Cambio Entregado:</span>
                <span>${{ number_format($sale->payments->first()->change_amount ?? 0, 2) }}</span>
            </li>
        </ul>
    </div>
</div>
@endsection