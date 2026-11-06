@extends('layouts.dashboard')

@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>Apertura de Turno de Caja</h2>
        <p>Inicie un nuevo turno asignando una terminal y declarando el fondo de caja inicial.</p>
    </div>
    <div class="dashboard-header__actions">
        <x-bmdc-button type="link" href="{{ route('admin.shifts.index') }}" variant="outlined">
            <x-slot name="mIcon">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>
            </x-slot>
            Volver
        </x-bmdc-button>
    </div>
</div>

<div class="form-panel" style="max-width: 600px; margin: 0 auto; padding: 25px;">
    <form action="{{ route('admin.shifts.store') }}" method="POST">
        @csrf

        <div class="form-group" style="margin-bottom: 20px;">
            <label for="terminal_id" style="font-weight: 500; display: block; margin-bottom: 8px;">Terminal POS *</label>
            <select name="terminal_id" id="terminal_id" class="form-control @error('terminal_id') is-invalid @enderror" required>
                <option value="">-- Seleccionar Terminal --</option>
                @foreach($terminals as $terminal)
                    <option value="{{ $terminal->id }}" {{ old('terminal_id') == $terminal->id ? 'selected' : '' }}>
                        {{ $terminal->name }} ({{ $terminal->store->name ?? 'Sucursal Principal' }})
                    </option>
                @endforeach
            </select>
            @error('terminal_id') <span class="invalid-feedback" style="color: red; font-size: 0.85rem;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label for="opening_cash" style="font-weight: 500; display: block; margin-bottom: 8px;">Efectivo Inicial (Fondo de Caja) *</label>
            <input type="number" step="0.01" name="opening_cash" id="opening_cash" class="form-control @error('opening_cash') is-invalid @enderror" value="{{ old('opening_cash', '0.00') }}" required autocomplete="off">
            @error('opening_cash') <span class="invalid-feedback" style="color: red; font-size: 0.85rem;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 25px;">
            <label for="notes" style="font-weight: 500; display: block; margin-bottom: 8px;">Notas de Apertura (Opcional)</label>
            <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
        </div>

        <div class="form-group" style="text-align: right;">
            <x-bmdc-button type="submit" variant="raised">
                Abrir Turno y Comenzar
            </x-bmdc-button>
        </div>
    </form>
</div>
@endsection