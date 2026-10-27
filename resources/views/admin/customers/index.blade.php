@extends('layouts.dashboard')

@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>Clientes Registrados</h2>
        <p>Administra clientes, asigna listas de precios y configura catálogos preferenciales.</p>
    </div>
    <div class="dashboard-header__actions">
        <x-bmdc-button type="link" href="{{ route('admin.customers.create') }}" variant="unelevated">
            <x-slot name="mIcon">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#ffffff"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            </x-slot>
            Nuevo Cliente
        </x-bmdc-button>
    </div>
</div>

<div class="form-panel" style="padding: 15px; margin-bottom: 15px;">
    <form action="{{ route('admin.customers.index') }}" method="GET">
        <div style="display: flex; gap: 10px; margin-bottom: 10px;">
            <input type="text" name="search" class="form-control" placeholder="Buscar por nombre, correo, teléfono..." value="{{ request('search') }}" autofocus autocomplete="off" style="flex: 1;">
            <button type="submit" class="btn btn-secondary">Buscar</button>
            @if(request('search'))
                <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary" style="background-color: #e0e0e0; color: #333;">Limpiar</a>
            @endif
        </div>
    </form>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>Código</th>
            <th>Nombre / Empresa</th>
            <th>Teléfono</th>
            <th>Lista de Precios</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($customers as $customer)
        <tr>
            <td>{{ $customer->code }}</td>
            <td><strong>{{ $customer->display_name }}</strong></td>
            <td>{{ $customer->phone ?? '-' }}</td>
            <td>{{ $customer->priceList ? $customer->priceList->name : 'General (Tienda)' }}</td>
            <td>
                <span style="color: {{ $customer->is_active ? 'green' : 'red' }}; font-weight: bold;">
                    {{ $customer->is_active ? 'Activo' : 'Inactivo' }}
                </span>
            </td>
            <td class="action-links">
                <a href="{{ route('admin.customers.edit', $customer->id) }}">
                    <x-table-action-button type="button" action="edit">Editar</x-table-action-button>
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align: center; padding: 20px;">No se encontraron clientes registrados.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top: 20px;">
    {{ $customers->links('partials.custom-pagination') }}
</div>
@endsection