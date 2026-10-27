@extends('layouts.dashboard')

@push('scripts')
<script src="{{ asset('js/modules/label_printer_module.js') }}"></script>
@endpush

@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>Módulo de Impresión de Etiquetas</h2>
        <p>Busca productos, especifica el peso o número de piezas e imprime etiquetas al instante.</p>
    </div>
</div>

<div class="form-panel" data-jscontroller="LabelPrinterController">
    <!-- CSRF token flag -->
    <input type="hidden" id="csrf_token" content="{{ csrf_token() }}">

    <!-- Search Section (Hidden when product is active/selected) -->
    <div id="printer_search_section">
        <div class="form-row" style="margin-bottom: 20px;">
            <div class="form-col" style="flex-grow: 2;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="printer_search_input">Buscar Producto</label>
                    <input type="text" id="printer_search_input" class="form-control" placeholder="Escribe nombre, código de barras o SKU..." data-jsinput="@handleSearch" autocomplete="off" autofocus>
                </div>
            </div>
            <div class="form-col">
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="printer_dept_select">Departamento</label>
                    <select id="printer_dept_select" class="form-control" data-jschange="@handleSearch">
                        <option value="">-- Todos los Departamentos --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Product Grid Results Container -->
        <div id="printer_product_grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 15px; max-height: 400px; overflow-y: auto; padding: 5px;">
            <!-- Dynamically rendered via TemplateEngine -->
        </div>
    </div>

    <!-- Selected Product Print Configuration Panel (Initially Hidden) -->
    <div id="printer_config_panel" style="display: none; margin-top: 15px; padding-top: 10px;">
        <h3 style="margin-bottom: 15px; font-size: 1.1rem; color: var(--text-main);">Configurar e Imprimir Etiqueta</h3>
        
        <div style="background: #f9fafb; padding: 15px; border-radius: 8px; margin-bottom: 15px; border: 1px solid #e5e7eb;">
            <p style="margin: 0; font-weight: bold; font-size: 1.1rem;" id="selected_product_name">-</p>
            <p style="margin: 5px 0 0 0; color: var(--text-muted); font-size: 0.9rem;">Código: <span id="selected_product_barcode">-</span> | Precio Base: $<span id="selected_product_price">0.00</span></p>
        </div>

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="print_weight" id="print_quantity_label">Peso / Cantidad *</label>
                    <input type="number" step="0.001" min="0.001" id="print_weight" class="form-control" value="1.000" required autocomplete="off">
                    <small style="color: var(--text-muted);" id="print_quantity_help">Ingrese el valor correspondiente.</small>
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="print_copies">Número de Copias *</label>
                    <input type="number" min="1" max="100" id="print_copies" class="form-control" value="1" required autocomplete="off">
                </div>
            </div>
        </div>
    
        <div class="form-group" style="display: flex; gap: 10px; margin-top: 15px;">
            <x-bmdc-button type="button" variant="raised" data-jsclick="@executePrint">
                Imprimir Etiqueta
            </x-bmdc-button>
            <x-bmdc-button type="button" variant="outlined" data-jsclick="@cancelSelection">
                Cambiar de Producto
            </x-bmdc-button>
        </div>
    </div>
</div>
@endsection