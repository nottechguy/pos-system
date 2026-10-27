@extends('layouts.dashboard')

@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>Nuevo Cliente</h2>
        <p>Registra los datos del cliente y asigna listas de precios preferenciales.</p>
    </div>
    <div class="dashboard-header__actions">
        <x-bmdc-button type="link" href="{{ route('admin.customers.index') }}" variant="outlined">
            Volver
        </x-bmdc-button>
    </div>
</div>

<div class="form-panel">
    <form action="{{ route('admin.customers.store') }}" method="POST">
        @csrf

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="customer_type">Tipo de Cliente *</label>
                    <select name="customer_type" id="customer_type" class="form-control @error('customer_type') is-invalid @enderror" required onchange="toggleCustomerFields(this.value)">
                        <option value="individual" {{ old('customer_type') == 'individual' ? 'selected' : '' }}>Persona Física</option>
                        <option value="business" {{ old('customer_type') == 'business' ? 'selected' : '' }}>Empresa / Negocio</option>
                    </select>
                    @error('customer_type') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="price_list_id">Lista de Precios Asignada</label>
                    <select name="price_list_id" id="price_list_id" class="form-control @error('price_list_id') is-invalid @enderror">
                        <option value="">-- General (Precios por defecto) --</option>
                        @foreach($priceLists as $list)
                            <option value="{{ $list->id }}" {{ old('price_list_id') == $list->id ? 'selected' : '' }}>{{ $list->name }}</option>
                        @endforeach
                    </select>
                    @error('price_list_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-row" id="individual_fields">
            <div class="form-col">
                <div class="form-group">
                    <label for="first_name">Nombre(s) *</label>
                    <input type="text" name="first_name" id="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}">
                    @error('first_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="last_name">Apellidos *</label>
                    <input type="text" name="last_name" id="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}">
                    @error('last_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-group" id="business_fields" style="display: none;">
            <label for="business_name">Razón Social / Nombre del Negocio *</label>
            <input type="text" name="business_name" id="business_name" class="form-control @error('business_name') is-invalid @enderror" value="{{ old('business_name') }}">
            @error('business_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="phone">Teléfono</label>
                    <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="tax_id">RFC / Tax ID</label>
                    <input type="text" name="tax_id" id="tax_id" class="form-control @error('tax_id') is-invalid @enderror" value="{{ old('tax_id') }}" style="text-transform: uppercase;">
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col" style="padding-top: 10px;">
                <label style="display: block; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}> Cliente Activo
                </label>
            </div>
        </div>

        <div class="form-group form-group-send" style="margin-top: 20px;">
            <x-bmdc-button type="submit" variant="raised">Guardar Cliente</x-bmdc-button>
        </div>
    </form>
</div>

<script>
    function toggleCustomerFields(type) {
        var individual = document.getElementById('individual_fields');
        var business = document.getElementById('business_fields');
        if (type === 'individual') {
            individual.style.display = 'flex';
            business.style.display = 'none';
        } else {
            individual.style.display = 'none';
            business.style.display = 'block';
        }
    }
    document.addEventListener("DOMContentLoaded", function() {
        toggleCustomerFields(document.getElementById('customer_type').value);
    });
</script>
@endsection