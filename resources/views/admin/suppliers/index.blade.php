@extends('layouts.dashboard')

@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>Catálogo de Proveedores</h2>
        <p>Gestión de proveedores, datos fiscales y contactos comerciales.</p>
    </div>
    <div class="dashboard-header__actions">
        <x-bmdc-button type="link" href="{{ route('admin.suppliers.create') }}" variant="unelevated">
            Nuevo Proveedor
        </x-bmdc-button>
    </div>
</div>

<div class="form-panel" style="padding: 15px; margin-bottom: 15px;">
    <form action="{{ route('admin.suppliers.index') }}" method="GET" style="display: flex; gap: 10px;">
        <input type="text" name="search" class="form-control" placeholder="Buscar por razón social o RFC..." value="{{ request('search') }}" style="flex: 1;">
        <button type="submit" class="btn btn-secondary">Buscar</button>
    </form>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>Razón Social</th>
            <th>Nombre Comercial</th>
            <th>RFC</th>
            <th>Teléfono</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($suppliers as $supplier)
        <tr>
            <td><strong>{{ $supplier->legal_name }}</strong></td>
            <td>{{ $supplier->trade_name ?? '-' }}</td>
            <td>{{ $supplier->tax_id ?? '-' }}</td>
            <td>{{ $supplier->phone ?? '-' }}</td>
            <td>
                <span style="color: {{ $supplier->is_active ? 'green' : 'red' }}; font-weight: bold;">
                    {{ $supplier->is_active ? 'Activo' : 'Inactivo' }}
                </span>
            </td>
            <td class="action-links">
                <a href="{{ route('admin.suppliers.edit', $supplier->id) }}">
                    <x-table-action-button type="button" action="edit">Editar</x-table-action-button>
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align: center; padding: 20px;">No se encontraron proveedores registrados.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top: 20px;">
    {{ $suppliers->links('partials.custom-pagination') }}
</div>
@endsection