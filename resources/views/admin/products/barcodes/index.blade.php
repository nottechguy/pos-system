@extends('layouts.dashboard')

@push('scripts')
<script>
    function toggleEditRow(id) {
        var displayRow = document.getElementById('barcode_row_' + id);
        var editRow = document.getElementById('barcode_edit_row_' + id);
        
        if (editRow.style.display === 'none') {
            editRow.style.display = 'table-row';
            displayRow.style.display = 'none';
        } else {
            editRow.style.display = 'none';
            displayRow.style.display = 'table-row';
        }
    }
</script>
@endpush

@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>Gestión de Códigos de Barras</h2>
        <p>Administra códigos alternativos, factores de conversión por unidad y código primario para: <strong>{{ $product->name }}</strong></p>
    </div>
    <div class="dashboard-header__actions">
        <x-bmdc-button type="link" href="{{ route('admin.products.edit', $product->id) }}" variant="outlined">
            <x-slot name="mIcon">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>
            </x-slot>
            Volver al Producto
        </x-bmdc-button>
    </div>
</div>

@if($errors->has('barcode'))
    <div class="alert alert-danger" style="margin-bottom: 15px; padding: 12px; background: #fee2e2; color: #991b1b; border-radius: 6px;">
        {{ $errors->first('barcode') }}
    </div>
@endif

<!-- Add New Barcode Panel -->
<div class="form-panel" style="margin-bottom: 25px;">
    <h3 style="margin-bottom: 15px; font-size: 1.1rem; color: var(--text-main);">Agregar Nuevo Código de Barras</h3>
    
    <form action="{{ route('admin.products.barcodes.store', $product->id) }}" method="POST">
        @csrf

        <div class="form-row">
            <div class="form-col" style="flex-grow: 1.5;">
                <div class="form-group">
                    <label for="barcode">Código de Barras / SKU *</label>
                    <input type="text" name="barcode" id="barcode" class="form-control @error('barcode') is-invalid @enderror" value="{{ old('barcode') }}" placeholder="Ej. 7501000123456" required autocomplete="off" autofocus>
                    @error('barcode') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-col">
                <div class="form-group">
                    <label for="unit_id">Unidad de Medida *</label>
                    <select name="unit_id" id="unit_id" class="form-control @error('unit_id') is-invalid @enderror" required>
                        <option value="">-- Seleccionar Unidad --</option>
                        @foreach($units as $u)
                            <option value="{{ $u->id }}" {{ old('unit_id', $product->sale_unit_id) == $u->id ? 'selected' : '' }}>
                                {{ ucwords(__("messages.measurement_units.{$u->code}.name")) }} ({{ __("messages.measurement_units.{$u->code}.symbol") }})
                            </option>
                        @endforeach
                    </select>
                    @error('unit_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-col">
                <div class="form-group">
                    <label for="conversion_factor">Factor de Conversión *</label>
                    <input type="number" step="0.000001" min="0.000001" name="conversion_factor" id="conversion_factor" class="form-control @error('conversion_factor') is-invalid @enderror" value="{{ old('conversion_factor', '1.000000') }}" required>
                    @error('conversion_factor') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col" style="padding-top: 10px;">
                <label style="display: block; cursor: pointer;">
                    <input type="checkbox" name="is_primary" value="1" {{ old('is_primary') ? 'checked' : '' }}> Establecer como código primario
                </label>
            </div>
        </div>

        <div class="form-group form-group-send" style="margin-top: 15px;">
            <x-bmdc-button type="submit" variant="raised">
                Registrar Código
            </x-bmdc-button>
        </div>
    </form>
</div>

<!-- Registered Barcodes List -->
<div class="form-panel">
    <h3 style="margin-bottom: 15px; font-size: 1.1rem; color: var(--text-main);">Códigos Registrados</h3>

    <table class="data-table">
        <thead>
            <tr>
                <th>Código / Barras</th>
                <th>Unidad Asociada</th>
                <th>Factor de Conversión</th>
                <th>Estatus Primario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($product->barcodes as $code)
                <!-- Display Row -->
                <tr id="barcode_row_{{ $code->id }}">
                    <td><strong>{{ $code->barcode }}</strong></td>
                    <td>
                        @if($code->unit)
                            {{ ucwords(__("messages.measurement_units.{$code->unit->code}.name")) }} ({{ __("messages.measurement_units.{$code->unit->code}.symbol") }})
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ number_format($code->conversion_factor, 4) }}</td>
                    <td>
                        @if($code->is_primary)
                            <span style="background: #d1fae5; color: #065f46; padding: 3px 8px; border-radius: 4px; font-weight: bold; font-size: 0.85rem;">Primario</span>
                        @else
                            <span style="color: var(--text-muted);">Secundario</span>
                        @endif
                    </td>
                    <td class="action-links" style="display: flex; gap: 8px; align-items: center;">
                        <button type="button" class="btn btn-secondary" style="padding: 4px 10px; font-size: 0.8rem;" onclick="toggleEditRow({{ $code->id }})">Editar</button>

                        <form action="{{ route('admin.products.barcodes.destroy', [$product->id, $code->id]) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este código de barras?');" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <x-table-action-button type="submit" action="delete">Eliminar</x-table-action-button>
                        </form>
                    </td>
                </tr>

                <!-- Hidden Inline Edit Form Row -->
                <tr id="barcode_edit_row_{{ $code->id }}" style="display: none; background: #f9fafb;">
                    <td colspan="5" style="padding: 15px;">
                        <form action="{{ route('admin.products.barcodes.update', [$product->id, $code->id]) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: flex-end;">
                                <div style="flex: 1; min-width: 180px;">
                                    <label style="font-size: 0.8rem; font-weight: bold;">Código / Barras</label>
                                    <input type="text" name="barcode" class="form-control" value="{{ $code->barcode }}" required autocomplete="off" style="padding: 6px;">
                                </div>

                                <div style="flex: 1; min-width: 160px;">
                                    <label style="font-size: 0.8rem; font-weight: bold;">Unidad</label>
                                    <select name="unit_id" class="form-control" required style="padding: 6px;">
                                        @foreach($units as $u)
                                            <option value="{{ $u->id }}" {{ $code->unit_id == $u->id ? 'selected' : '' }}>
                                                {{ ucwords(__("messages.measurement_units.{$u->code}.name")) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div style="width: 120px;">
                                    <label style="font-size: 0.8rem; font-weight: bold;">Factor</label>
                                    <input type="number" step="0.000001" min="0.000001" name="conversion_factor" class="form-control" value="{{ $code->conversion_factor }}" required style="padding: 6px;">
                                </div>

                                <div style="display: flex; align-items: center; height: 38px;">
                                    <label style="font-size: 0.85rem; cursor: pointer; display: flex; gap: 5px; align-items: center;">
                                        <input type="checkbox" name="is_primary" value="1" {{ $code->is_primary ? 'checked' : '' }}> Primario
                                    </label>
                                </div>

                                <div style="display: flex; gap: 6px;">
                                    <button type="submit" class="btn btn-secondary" style="background: #059669; color: white; padding: 6px 12px; font-size: 0.85rem;">Guardar</button>
                                    <button type="button" class="btn btn-secondary" style="background: #e5e7eb; color: #374151; padding: 6px 12px; font-size: 0.85rem;" onclick="toggleEditRow({{ $code->id }})">Cancelar</button>
                                </div>
                            </div>
                        </form>
                    </td>
                </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 20px; color: var(--text-muted);">No hay códigos de barras adicionales registrados para este producto.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection