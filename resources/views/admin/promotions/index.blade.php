@extends('layouts.dashboard')

@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>Módulo de Promociones</h2>
        <p>Gestión de ofertas, precios especiales por paquete, pieza o mayoreo y límites de vigencia.</p>
    </div>
    <div class="dashboard-header__actions">
        <x-bmdc-button type="link" href="{{ route('admin.promotions.create') }}" variant="unelevated">
            <x-slot name="mIcon">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#ffffff"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            </x-slot>
            Nueva Promoción
        </x-bmdc-button>
    </div>
</div>

<div class="form-panel" style="padding: 15px; margin-bottom: 15px;">
    <form action="{{ route('admin.promotions.index') }}" method="GET" style="display: flex; gap: 10px;">
        <input type="text" name="search" class="form-control" placeholder="Buscar por nombre de promoción..." value="{{ request('search') }}" style="flex: 1;" autocomplete="off" spellcheck="off">
        <x-bmdc-button type="submit" variant="outlined">Buscar</x-bmdc-button>
    </form>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Tipo</th>
            <th>Precio Promo</th>
            <th>Vigencia</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($promotions as $promo)
        <tr>
            <td><strong>{{ $promo->name }}</strong></td>
            <td><span style="font-size: 0.85rem; background: #e5e7eb; padding: 2px 6px; border-radius: 4px;">{{ __("messages.promotions.{$promo->promotion_type}.name") }}</span></td>
            <td><strong>{{ $promo->display_price }}</strong></td>
            <td>{{ $promo->starts_at?->format('d/m/Y') ?? 'Inicia hoy' }} - {{ $promo->ends_at?->format('d/m/Y') ?? 'Indefinido' }}</td>
            <td>
                <span style="color: {{ $promo->is_active ? 'green' : 'red' }}; font-weight: bold;">
                    {{ $promo->is_active ? 'Activa' : 'Inactiva' }}
                </span>
            </td>
            <td class="action-links" style="display: flex; gap: 8px; align-items: center;">
                <a href="{{ route('admin.promotions.show', $promo->id) }}">
                    <x-table-action-button type="button" action="view">Ver</x-table-action-button>
                </a>
                <a href="{{ route('admin.promotions.edit', $promo->id) }}">
                    <x-table-action-button type="button" action="edit">Editar</x-table-action-button>
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align: center; padding: 20px;">No se encontraron promociones registradas.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top: 20px;">
    {{ $promotions->links('partials.custom-pagination') }}
</div>
@endsection