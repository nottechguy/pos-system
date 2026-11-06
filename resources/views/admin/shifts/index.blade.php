@extends('layouts.dashboard')

@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>Gestión de Turnos de Caja</h2>
        <p>Historial y control de turnos abiertos y cerrados en las terminales.</p>
    </div>
    <div class="dashboard-header__actions" style="display: flex; gap: 10px;">
        <x-bmdc-button type="link" href="{{ route('admin.shifts.create') }}" variant="unelevated">
            <x-slot name="mIcon">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#ffffff"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            </x-slot>
            Abrir Nuevo Turno
        </x-bmdc-button>
    </div>
</div>

<!-- Filter Bar -->
<div class="form-panel" style="padding: 15px; margin-bottom: 20px;">
    <form action="{{ route('admin.shifts.index') }}" method="GET" style="display: flex; gap: 15px; align-items: flex-end;">
        <div style="flex: 1; max-width: 250px;">
            <label for="status" style="font-size: 0.85rem; font-weight: 500; display: block; margin-bottom: 5px;">Filtrar por Estado:</label>
            <select name="status" id="status" class="form-control" onchange="this.form.submit()">
                <option value="">-- Todos los estados --</option>
                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Abierto</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Cerrado</option>
            </select>
        </div>
        @if(request('status'))
            <div>
                <a href="{{ route('admin.shifts.index') }}" class="btn btn-secondary" style="height: 38px; line-height: 38px; background: #e0e0e0; color: #333; text-decoration: none; display: inline-block; padding: 0 15px;">Limpiar</a>
            </div>
        @endif
    </form>
</div>

<div style="width: 100%; overflow-x: auto;  -webkit-overflow-scrolling: touch; ">
<table class="data-table" style="white-space: nowrap;">
    <thead>
        <tr>
            <th>Turno</th>
            <th>Terminal / Sucursal</th>
            <th>Cajero (Apertura)</th>
            <th>Efectivo Inicial</th>
            <th>Efectivo Esperado</th>
            <th>Efectivo Conteo</th>
            <th>Diferencia</th>
            <th>Estado</th>
            <th>Apertura</th>
            <th style="text-align: right;">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($shifts as $shift)
        <tr>
            <td><strong>#{{ $shift->id }}</strong></td>
            <td>{{ $shift->terminal->name ?? 'N/A' }}</td>
            <td>{{ $shift->opener->full_name ?? 'N/A' }}</td>
            <td>${{ number_format($shift->opening_cash, 2) }}</td>
            <td>${{ number_format($shift->expected_cash ?? 0, 2) }}</td>
            <td>${{ number_format($shift->counted_cash ?? 0, 2) }}</td>
            <td style="color: {{ ($shift->difference ?? 0) < 0 ? 'red' : (($shift->difference ?? 0) > 0 ? 'green' : 'inherit') }}; font-weight: bold;">
                ${{ number_format($shift->difference ?? 0, 2) }}
            </td>
            <td>
                <span style="color: {{ $shift->status === 'open' ? 'green' : 'gray' }}; font-weight: bold; text-transform: uppercase;">
                    {{ $shift->status }}
                </span>
            </td>
            <td>{{ $shift->opened_at ? $shift->opened_at->format('d/m/Y H:i') : '-' }}</td>
            <td class="action-links" style="text-align: right;">
                <a href="{{ route('admin.shifts.summary', $shift->id) }}">
                    <x-table-action-button type="button" action="view">Ver Resumen</x-table-action-button>
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="10" style="text-align: center; padding: 20px;">No hay turnos registrados.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top: 20px;">
    {{ $shifts->links('partials.custom-pagination') }}
</div>
</div>

@endsection