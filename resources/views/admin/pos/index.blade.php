@extends('layouts.pos')

@section('title', 'POS Terminal - ' . $activeShift->terminal->name)

@section('content')
<x-pos.appbar />
<div class="pos-panel" 
     data-jscontroller="PosUIController" 
     data-store-id="{{ $activeShift->store_id }}"
     data-terminal-id="{{ $activeShift->terminal_id }}">

    <div class="pos-layout">

        <div class="pos-cart-section" tabindex="0" id="pos_cart_section">
            <div class="pos-cart-section__columns">
                <div class="section-columns section__columns--table">
                    <div class="pos-products-section" id="pos_products_section">
                        <div class="pos-products-section--search">
                            <div class="form-group search-bar" style="display: flex; gap: 8px; align-items: center;">
                                <input type="text" id="pos_search_input" class="form-control" placeholder="Escanea un código de barras o escribe para buscar..." data-jskeyup="@handleSearchInput" autocomplete="off" autofocus style="flex: 1;">
                                <!-- Promoción Button (Opens Promotion Modal, Shortcut: 'p') -->
                                <button type="button" class="btn btn-secondary" data-jscontroller="PosUIController" data-jsclick="@openPromotionModal" title="Seleccionar Promoción (P)" style="display: flex; align-items: center; gap: 4px; padding: 0.5rem 0.75rem; white-space: nowrap; background: #7c3aed; color: #fff; border: none; border-radius: 4px; cursor: pointer; margin-right: 4px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 20 20" height="18px" viewBox="1 1 18 18" width="18px" fill="#ffffff"><g><g><rect fill="none" height="20" width="20"/></g></g><g><path d="M17.56,9.58l-7.14-7.14C10.14,2.16,9.76,2,9.36,2H3.5C2.67,2,2,2.67,2,3.5v5.86c0,0.4,0.16,0.78,0.44,1.06l7.14,7.14 c0.59,0.59,1.54,0.59,2.12,0l5.86-5.86C18.15,11.11,18.15,10.17,17.56,9.58z M5.5,6.75c-0.69,0-1.25-0.56-1.25-1.25 S4.81,4.25,5.5,4.25S6.75,4.81,6.75,5.5S6.19,6.75,5.5,6.75z"/></g></svg>
                                    Promoción (P)
                                </button>

                                <button type="button" class="btn btn-secondary" data-jscontroller="PosUIController" data-jsclick="@openCustomerModal" title="Seleccionar Cliente (C)" style="display: flex; align-items: center; gap: 4px; padding: 0.5rem 0.75rem; white-space: nowrap; background: #2563eb; color: #fff; border: none; border-radius: 4px; cursor: pointer; margin-right: 4px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="#FFFFFF"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"/></svg>
                                    Cliente (C)
                                </button>

                                <!-- Scale Button (Opens Digital Scale Modal, Shortcut: 'b') -->
                                <button type="button" class="btn btn-secondary" data-jscontroller="PosUIController" data-jsclick="@openScaleModal" title="Abrir Báscula (B)" style="display: flex; align-items: center; gap: 4px; padding: 0.5rem 0.75rem; white-space: nowrap; background: #059669; color: #fff; border: none; border-radius: 4px; cursor: pointer;">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="#FFFFFF"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm40-80h480q17 0 28.5-11.5T760-360v-280q0-17-11.5-28.5T720-680H240q-17 0-28.5 11.5T200-640v280q0 17 11.5 28.5T240-280Zm0-360h480v-120H200v120Zm240 200q25 0 42.5-17.5T500-480q0-25-17.5-42.5T440-540q-25 0-42.5 17.5T380-480q0 25 17.5 42.5T440-440ZM200-200v-560 560Z"/></svg>
                                    Báscula (B)
                                </button>
                            </div>
                            <div class="product-grid" id="pos_product_grid" style="max-height: calc(100vh - 150px);">
                                <!-- JS will render search results or frequent items here -->
                            </div>

                            <div id="pos_active_customer_container" style="margin-top: 10px; display: none; padding: 8px 12px; background: #e0f2fe; border: 1px solid #bae6fd; border-radius: 6px; color: #0369a1; font-weight: 600; display: none; justify-content: space-between; align-items: center;">
                                <span id="pos_active_customer_name"></span>
                                <button type="button" data-jscontroller="PosUIController" data-jsclick="@clearCustomer" style="background: none; border: none; color: #ef4444; cursor: pointer; font-weight: bold;" title="Quitar cliente">X</button>
                            </div>
                        </div>
                    </div>

                    <div class="pos-products-section" id="pos_products_table_conntainer">
                        <div class="pos-products__table">
                            <div class="pos-products__table-column pos-products__table-column--article" style="flex: 1">Precio</div>
                            <div class="pos-products__table-column pos-products__table-column--article">Artículos</div>
                            <div class="pos-products__table-column pos-products__table-column--quantity">Cantidad</div>
                            <div class="pos-products__table-column pos-products__table-column--total">Total</div>
                        </div>
                        <div class="cart-items-container" id="pos_cart_items">
                            <!-- JS will asynchronously render scanned items here -->
                            <div class="empty-cart-message">Escanea o busca un producto para comenzar</div>
                        </div>
                    </div>
                </div>

                <div class="section-columns section__columns--checkout">
                    <div class="pos-column__checkout">
                        <div class="cart-header">
                            <!-- Print Mode Select Dropdown -->
                            <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                <label for="ticket_mode_select" style="font-size: 0.75rem; font-weight: 600; color: #4b5563;">Seleccionar modo de impresión (M)</label>
                                <select id="ticket_mode_select" class="form-control" style="padding: 0.25rem 0.5rem; font-size: 0.9rem; border-radius: 4px; border: 1px solid #d1d5db;" data-jscontroller="PosUIController" data-jschange="@handleModeChange">
                                    <option value="Ticket" selected>Ticket</option>
                                    <option value="Nota de Venta">Nota de Venta</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="pos-column__checkout pos-column__products_ads">
                        <div></div>
                    </div>
                    <div class="pos-column__checkout">
                        <div class="totals-row">
                            <span>Subtotal:</span>
                            <span id="cart_subtotal">$0.00</span>
                        </div>
                        <div class="totals-row">
                            <span>Descuento:</span>
                            <span id="cart_discount">$0.00</span>
                        </div>
                        <div class="totals-row grand-total">
                            <span>Total:</span>
                            <span id="cart_total">$0.00</span>
                        </div>
                        
                        <div class="cart-actions">
                            <x-bmdc-button data-jsclick="@clearCart" variant="outlined">Cancelar</x-bmdc-button>
                            <x-bmdc-button data-jsclick="@openPaymentModal" variant="raised">Cobrar (F5)</x-bmdc-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection