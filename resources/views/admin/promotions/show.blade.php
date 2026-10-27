@extends('layouts.dashboard')

@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>Detalles de la Promoción</h2>
        <p>Visualiza la información general, el banner y los productos incluidos en esta promoción.</p>
    </div>
    <div class="dashboard-header__actions" style="display: flex; gap: 10px;">
        <x-bmdc-button type="link" href="{{ route('admin.promotions.edit', $promotion->id) }}" variant="raised">
            Editar
        </x-bmdc-button>
        <x-bmdc-button type="link" href="{{ route('admin.promotions.index') }}" variant="outlined">
            Volver
        </x-bmdc-button>
    </div>
</div>

<div class="form-panel" style="max-width: 900px; margin: 0 auto;">
    
    @if($promotion->banner_image)
        <div class="form-group" style="text-align: center; margin-bottom: 25px;">
            <label style="display: block; margin-bottom: 8px; font-weight: bold;">Banner Promocional:</label>
            <img src="{{ asset('storage/' . $promotion->banner_image) }}" alt="{{ $promotion->name }}" style="max-width: 100%; max-height: 250px; border-radius: 8px; border: 1px solid #e5e7eb; object-fit: cover;">
        </div>
    @endif

    <div class="form-row">
        <div class="form-col">
            <div class="form-group">
                <label style="font-weight: bold; color: var(--text-muted);">Nombre de la Promoción</label>
                <p style="font-size: 1.1rem; font-weight: 600; color: #1f2937; margin-top: 4px;">{{ $promotion->name }}</p>
            </div>
        </div>
        <div class="form-col">
            <div class="form-group">
                <label style="font-weight: bold; color: var(--text-muted);">Tipo de Promoción</label>
                <p style="font-size: 1rem; margin-top: 4px;">
                    @if($promotion->promotion_type == 'single')
                        <span class="badge badge-info">Individual (Single)</span>
                    @elseif($promotion->promotion_type == 'pack')
                        <span class="badge badge-success">Paquete (Pack)</span>
                    @else
                        <span class="badge badge-warning">Mayoreo / Bulto (Bulk)</span>
                    @endif
                </p>
            </div>
        </div>
        <div class="form-col">
            <div class="form-group">
                <label style="font-weight: bold; color: var(--text-muted);">Estado</label>
                <p style="margin-top: 4px;">
                    @if($promotion->is_active)
                        <span style="color: #059669; font-weight: 600;">● Activa</span>
                    @else
                        <span style="color: #dc2626; font-weight: 600;">● Inactiva</span>
                    @endif
                </p>
            </div>
        </div>
    </div>

    <div class="form-row" style="margin-top: 15px;">
        <div class="form-col">
            <div class="form-group">
                <label style="font-weight: bold; color: var(--text-muted);">Precio Promocional Global</label>
                <p style="font-size: 1.1rem; font-weight: 600; color: #059669; margin-top: 4px;">
                    {{ $promotion->promotional_price > 0 ? '$' . number_format($promotion->promotional_price, 2) : 'Variable / Por Producto' }}
                </p>
            </div>
        </div>
        <div class="form-col">
            <div class="form-group">
                <label style="font-weight: bold; color: var(--text-muted);">Cantidad Mínima Requerida</label>
                <p style="font-size: 1rem; margin-top: 4px;">{{ number_format($promotion->minimum_quantity, 2) }}</p>
            </div>
        </div>
        <div class="form-col">
            <div class="form-group">
                <label style="font-weight: bold; color: var(--text-muted);">Creado por</label>
                <p style="font-size: 1rem; margin-top: 4px;">{{ $promotion->creator?->name ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <!-- Products Included Table -->
    <div class="form-group" style="margin-top: 25px;">
        <label style="font-weight: bold; color: var(--text-muted); display: block; margin-bottom: 8px;">Productos Incluidos en la Promoción ({{ $promotion->products->count() }})</label>
        <table class="data-table" style="font-size: 0.9rem;">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>SKU</th>
                    <th>Precio Base</th>
                    <th>Precio Promocional</th>
                    <th>Descuento</th>
                </tr>
            </thead>
            <tbody>
                @forelse($promotion->products as $product)
                    <tr>
                        <td><strong>{{ $product->name }}</strong></td>
                        <td>{{ $product->sku }}</td>
                        <td>${{ number_format($product->sale_price ?? 0, 2) }}</td>
                        <td>
                            @if($product->pivot && $product->pivot->promotional_price > 0)
                                <strong style="color: #059669;">${{ number_format($product->pivot->promotional_price, 2) }}</strong>
                            @else
                                <span style="color: var(--text-muted);">-</span>
                            @endif
                        </td>
                        <td>
                            @if($product->pivot && $product->pivot->discount_value > 0)
                                <strong style="color: #7c3aed;">${{ number_format($product->pivot->discount_value, 2) }}</strong>
                            @else
                                <span style="color: var(--text-muted);">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 15px;">No hay productos asociados a esta promoción.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection