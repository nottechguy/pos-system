@extends('layouts.scale')

@section('content')
<div class="scale-panel pos-panel" data-jscontroller="ScaleUIController">

    <div class="scale-layout pos-layout" style="padding: 0">
        
        <!-- Left Panel: Product Search & Filter -->
        <div class="scale-section scale-products-section">
            <div class="search-header">
                <h3>Seleccionar producto a granel</h3>
            </div>

            <div class="form-group search-bar" style="display: flex; gap: 10px; margin-bottom: 15px;">
                <input type="text" id="scale_search_input" class="form-control" placeholder="Buscar producto por nombre..." data-jskeyup="@handleSearch" autocomplete="off" autofocus="true">
                
                <select id="scale_dept_select" class="form-control" data-jschange="@handleSearch" style="flex: 1">
                    <option value="">Todos los departamentos</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="product-grid" id="scale_product_grid">
                <!-- Search results populate here -->
            </div>

            <div class="product-footer">
                    <img src="{{ Storage::url('img/logo_x1.png') }}">
                    <span>{{ config('app.name') }}</span>
            </div>
        </div>

        <!-- Right Panel: Active Scale Status -->
        <div class="scale-section pos-cart-section" style="width: 55%; background: #f9fafb; display: flex; align-items: center; justify-content: center; padding: 2rem;">
            
            <div id="scale_idle_state" style="text-align: center; color: #666;">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="7.5 4.21 12 6.81 16.5 4.21"></polyline><polyline points="7.5 19.79 7.5 14.6 3 12"></polyline><polyline points="21 12 16.5 14.6 16.5 19.79"></polyline><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                <h2>Esperando la selección del producto</h2>
                <p>Seleccione un producto de la lista para activar la báscula.</p>
            </div>

            <div id="scale_armed_state" style="display: none; text-align: center; width: 100%;">
                <div style="background: #fff; padding: 3rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    <h1 id="armed_product_name" style="font-size: 2rem; margin-bottom: 0.5rem; color: #333;">Product Name</h1>
                    <p id="armed_product_barcode" style="display: none; color: #888; font-family: monospace; font-size: 1.2rem; margin-bottom: 0.5rem;">BARCODE</p>
                    <p style="color: #555; font-size: 1.1rem; margin-bottom: 2rem;">Precio Unitario: $<span id="armed_product_price">0.00</span> / kg</p>
                    
                    <!-- Live Weight & Price Readouts -->
                    <div style="display: flex; justify-content: center; gap: 2rem; margin: 1.5rem auto;">
                        <!-- Weight Display -->
                        <div style="background: #222; color: #0f0; font-family: monospace; font-size: 3.5rem; padding: 1rem 2rem; border-radius: 8px; text-shadow: 0 0 10px #0f0; border: 4px solid #111;">
                            <span class="live_weight_display">0.000</span> <span style="font-size: 1.5rem; color: #888;">kg</span>
                        </div>
                        
                        <!-- Price Display -->
                        <div style="background: #222; color: #0ff; font-family: monospace; font-size: 3.5rem; padding: 1rem 2rem; border-radius: 8px; text-shadow: 0 0 10px #0ff; border: 4px solid #111;">
                            <span style="font-size: 1.5rem; color: #888;">$</span><span class="live_price_display">0.00</span>
                        </div>
                    </div>

                    <div data-jscontroller="ScaleUIController" style="margin: 20px 0; display: flex; align-items: center; justify-content: center; gap: 12px; flex-direction: row;">
                        <x-bmdc-button type="button" variant="outlined" data-jsclick="@cancelArmedState">Cancelar / Seleccionar otro</x-bmdc-button>
                        <x-bmdc-button type="button" variant="raised"  data-jsclick="@printFromUI">Imprimir Etiqueta</x-bmdc-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection