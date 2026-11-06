@extends('layouts.dashboard')

@section('content')
@php
    // Calculate summary stats dynamically if controller passes shift relationship
    $totalSales = $shift->cashMovements()->where('movement_type', 'cash_sale')->sum('amount');
@endphp

<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>Resumen del Turno #{{ $shift->id }}</h2>
        <p>Terminal: <strong>{{ $shift->terminal->name ?? 'N/A' }}</strong> | Estado: <span style="text-transform: uppercase; color: {{ $shift->status === 'open' ? 'green' : 'gray' }};">{{ $shift->status }}</span></p>
    </div>
    <div class="dashboard-header__actions">
        <x-bmdc-button type="link" href="{{ route('admin.shifts.index') }}" variant="outlined">
            <x-slot name="mIcon">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>
            </x-slot>
            Volver a Turnos
        </x-bmdc-button>
    </div>
</div>

<!-- Shift KPI Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px; margin-bottom: 25px;">
    <div class="form-panel" style="padding: 20px; border-left: 4px solid #2563eb;">
        <div style="font-size: 0.85rem; color: #4b5563;">Efectivo Inicial</div>
        <div style="font-size: 1.5rem; font-weight: bold; color: #1f2937; margin-top: 5px;">${{ number_format($shift->opening_cash, 2) }}</div>
    </div>
    <div class="form-panel" style="padding: 20px; border-left: 4px solid #059669;">
        <div style="font-size: 0.85rem; color: #4b5563;">Efectivo Esperado en Caja</div>
        <div style="font-size: 1.5rem; font-weight: bold; color: #059669; margin-top: 5px;">${{ number_format($shift->expected_cash ?? 0, 2) }}</div>
    </div>
    <div class="form-panel" style="padding: 20px; border-left: 4px solid #d97706;">
        <div style="font-size: 0.85rem; color: #4b5563;">Efectivo Contado (Cierre)</div>
        <div style="font-size: 1.5rem; font-weight: bold; color: #d97706; margin-top: 5px;">${{ number_format($shift->counted_cash ?? 0, 2) }}</div>
    </div>
    <div class="form-panel" style="padding: 20px; border-left: 4px solid {{ ($shift->difference ?? 0) < 0 ? '#dc2626' : '#059669' }};">
        <div style="font-size: 0.85rem; color: #4b5563;">Diferencia (Sobrante / Faltante)</div>
        <div style="font-size: 1.5rem; font-weight: bold; color: {{ ($shift->difference ?? 0) < 0 ? '#dc2626' : '#059669' }}; margin-top: 5px;">${{ number_format($shift->difference ?? 0, 2) }}</div>
    </div>
</div>

<div class="cards-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
    <!-- Shift Details Panel -->
    <div class="form-panel" style="padding: 20px;">
        <h3 style="margin-top: 0; margin-bottom: 15px; font-size: 1.1rem;">Información del Turno</h3>
        <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.95rem;">
            <li style="padding: 8px 0; border-bottom: 1px solid #eee; display: flex; justify-content: space-between;">
                <span>Cajero de Apertura:</span>
                <strong>{{ $shift->opener->full_name ?? 'N/A' }}</strong>
            </li>
            <li style="padding: 8px 0; border-bottom: 1px solid #eee; display: flex; justify-content: space-between;">
                <span>Cajero de Cierre:</span>
                <strong>{{ $shift->closer->full_name ?? 'Pendiente / Activo' }}</strong>
            </li>
            <li style="padding: 8px 0; border-bottom: 1px solid #eee; display: flex; justify-content: space-between;">
                <span>Fecha de Apertura:</span>
                <span>{{ $shift->opened_at ? $shift->opened_at->format('d/m/Y H:i:s') : '-' }}</span>
            </li>
            <li style="padding: 8px 0; border-bottom: 1px solid #eee; display: flex; justify-content: space-between;">
                <span>Fecha de Cierre:</span>
                <span>{{ $shift->closed_at ? $shift->closed_at->format('d/m/Y H:i:s') : 'Turno aún abierto' }}</span>
            </li>
            @if($shift->notes)
            <li style="padding: 10px 0; margin-top: 5px;">
                <span style="display: block; font-weight: 500; margin-bottom: 3px;">Notas:</span>
                <p style="color: #4b5563; font-size: 0.9rem; background: #f9fafb; padding: 8px; border-radius: 4px;">{{ $shift->notes }}</p>
            </li>
            @endif
        </ul>
    </div>

    <!-- Count Lines Breakdown -->
    <div class="form-panel" style="padding: 20px;">
        <h3 style="margin-top: 0; margin-bottom: 15px; font-size: 1.1rem;">Desglose de Conteo de Efectivo</h3>
        <table class="data-table" style="width: 100%; font-size: 0.9rem;">
            <thead>
                <tr>
                    <th>Denominación</th>
                    <th style="text-align: center;">Cantidad</th>
                    <th style="text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($shift->countLines as $line)
                <tr>
                    <td>${{ number_format($line->denomination?->value ?? 0, 2) }}</td>
                    <td style="text-align: center;">{{ $line->quantity }}</td>
                    <td style="text-align: right;"><strong>${{ number_format($line->total, 2) }}</strong></td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center; padding: 15px;">No hay líneas de conteo registradas para este turno.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection