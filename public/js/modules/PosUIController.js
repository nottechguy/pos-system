defineModule("DialogManager", [], function (
    global,
    requireModule,
    requireDynamic,
    requireLazy,
    module,
    exports
) {
    'use strict';

    var activeModal = null;
    var lastFocusedElement = null;
    var keydownHandler = null;

    function getFocusableElements(container) {
        return Array.from(
            container.querySelectorAll(
                'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
            )
        );
    }

    function handleKeydown(e) {
        if (!activeModal) return;

        if (e.key === 'Escape' && activeModal.closeOnEscape !== false) {
            e.preventDefault();
            exports.close();
            return;
        }

        if (e.key === 'Tab') {
            var focusable = getFocusableElements(activeModal.element);
            if (focusable.length === 0) return;

            var first = focusable[0];
            var last = focusable[focusable.length - 1];

            if (e.shiftKey) {
                if (document.activeElement === first) {
                    e.preventDefault();
                    last.focus();
                }
            } else {
                if (document.activeElement === last) {
                    e.preventDefault();
                    first.focus();
                }
            }
        }
    }

    exports.open = function (modalElement, options) {
        options = options || {};
        if (activeModal) {
            exports.close();
        }

        lastFocusedElement = document.activeElement;
        activeModal = {
            element: modalElement,
            closeOnEscape: options.closeOnEscape !== false,
            closeCallback: options.onClose || null
        };

        // Click outside (backdrop) to close
        modalElement.addEventListener('click', function (e) {
            if (e.target === modalElement) {
                exports.close();
            }
        });

        keydownHandler = handleKeydown;
        document.addEventListener('keydown', keydownHandler);

        // Trap initial focus
        window.requestAnimationFrame(function () {
            var focusable = getFocusableElements(modalElement);
            if (focusable.length > 0) {
                focusable[0].focus();
            } else {
                modalElement.focus();
            }
        });
    };

    exports.close = function () {
        if (!activeModal) return;

        if (keydownHandler) {
            document.removeEventListener('keydown', keydownHandler);
            keydownHandler = null;
        }

        if (activeModal.element && activeModal.element.parentNode) {
            activeModal.element.remove();
        }

        if (typeof activeModal.closeCallback === 'function') {
            activeModal.closeCallback();
        }

        if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
            lastFocusedElement.focus();
        }

        activeModal = null;
        lastFocusedElement = null;
    };

    module.exports = exports;
}, null);

defineModule("PosTemplateRegister", ["TemplateEngine"], function (global, requireModule, requireDynamic, requireLazy, module, exports, TemplateEngine) {
    'use strict';

TemplateEngine.register("cashier_auth_modal", `
        <div class="modal-backdrop" id="cashier_auth_modal_wrapper" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); display: flex; justify-content: center; align-items: center; z-index: 1100;" tabindex="-1">
            <div class="modal-content" style="background: #fff; padding: 2rem; border-radius: 8px; width: 400px; max-width: 95vw; display: flex; flex-direction: column; box-shadow: 0 8px 24px rgba(0,0,0,0.2);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h3 style="margin: 0;">Autorizar Cambio de Cajero</h3>
                    <button type="button" data-jscontroller="POSCashierController" data-jsclick="@closeAuthModal" style="background: none; border: none; font-size: 1.25rem; cursor: pointer;">&times;</button>
                </div>
                <p style="font-size: 0.9rem; color: #4b5563; margin-bottom: 1rem;">Ingrese la contraseña o PIN de <strong>{{cashier_name}}</strong> para continuar:</p>
                
                <input type="hidden" id="auth_target_cashier_id" value="{{cashier_id}}">
                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <input type="password" id="cashier_credentials_input" class="form-control" placeholder="Contraseña o PIN" autocomplete="current-password" autofocus style="width: 100%; padding: 0.75rem; font-size: 1rem; border: 1px solid #d1d5db; border-radius: 6px;">
                    <span id="cashier_auth_error" class="invalid-feedback" style="display: none; color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem;"></span>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" class="btn btn-secondary" data-jscontroller="POSCashierController" data-jsclick="@closeAuthModal" style="padding: 0.5rem 1rem; cursor: pointer;">Cancelar</button>
                    <button type="button" class="btn btn-primary" data-jscontroller="POSCashierController" data-jsclick="@confirmSwitchCashier" style="padding: 0.5rem 1.5rem; background: #2e7d32; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Autorizar</button>
                </div>
            </div>
        </div>
    `);

    TemplateEngine.register("customer_modal", `
        <div class="modal-backdrop" data-jscontroller="PosUIController" id="customer_modal_wrapper" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); display: flex; justify-content: center; align-items: center; z-index: 1050;" tabindex="-1">
            <div class="modal-content" style="background: #fff; padding: 2rem; border-radius: 8px; width: 600px; max-width: 95vw; height: 450px; max-height: 90vh; display: flex; flex-direction: column; box-shadow: 0 8px 24px rgba(0,0,0,0.2);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-shrink: 0;">
                    <h3 style="margin: 0;">Seleccionar Cliente (Precios y Crédito)</h3>
                    <button type="button" data-jscontroller="PosUIController" data-jsclick="@closeCustomerModal" style="background: none; border: none; font-size: 1.25rem; cursor: pointer;">&times;</button>
                </div>
                <div style="margin-bottom: 1rem; flex-shrink: 0;">
                    <input type="text" id="customer_modal_search" class="form-control" placeholder="Buscar por nombre, teléfono, email..." data-jskeyup="@handleCustomerSearch" autocomplete="off" autofocus style="width: 100%; padding: 0.5rem; font-size: 1rem;">
                </div>
                <div id="customer_modal_grid" style="display: flex; flex-direction: column; gap: 10px; flex: 1; overflow-y: auto; border: 1px solid #e5e7eb; padding: 10px; border-radius: 6px;">
                    <div style="text-align: center; color: #6b7280; padding: 1rem;">Escriba para buscar clientes registrados</div>
                </div>
            </div>
        </div>
    `);

    TemplateEngine.register("customer_search_result", `
        <button type="button" class="product-card" data-jscontroller="PosUIController" data-jsclick="@selectCustomer" data-customer='{"id": {{id}}, "name": "{{name}}"}' style="padding: 10px; text-align: left; background: #fff; border: 1px solid #d1d5db; border-radius: 6px; cursor: pointer; display: flex; justify-content: space-between;">
            <div>
                <div style="font-weight: 600; font-size: 0.95rem; color: #1f2937;">{{name}}</div>
                <div style="color: #6b7280; font-size: 0.85rem; margin-top: 4px;">{{phone}} | {{email}}</div>
            </div>
            {{#if has_credit}}<span style="color: #059669; font-size: 0.8rem; font-weight: bold; background: #d1fae5; padding: 2px 6px; border-radius: 4px; height: fit-content;">CRÉDITO</span>{{/if}}
        </button>
    `);

    TemplateEngine.register("product_not_found", `
        <div style="padding: 20px">Producto no encontrado</div>
    `);

    TemplateEngine.register("cart_item", `
        <div class="cart-item" tabindex="0" data-id="{{id}}">
            <div class="cart-item-price">
                <div>{{price}}</div>
            </div>
            <div class="cart-item-details">
                <div class="cart-item-title" style="font-weight: 500; color: #1f2937;">{{name}}</div>
                {{#if has_discount}}
                <div class="cart-item-discount-tag" style="font-size: 0.75rem; color: #059669;">{{discount_name}}: -{{formatted_discount}}</div>
                {{/if}}
            </div>
            <div class="cart-item-quantity">
                <input type="number" class="cart-item-qty-input" value="{{quantity}}" data-id="{{id}}" data-jscontroller="PosUIController" data-jschange="@changeCartItemQty" min="{{min_qty}}" step="any">
                <span style="font-size: 0.85rem; color: #6b7280;">{{unit}}</span>
            </div>
            <div class="cart-item-total-col">
                <div class="cart-item-total">{{line_total}}</div>
            </div>
        </div>
    `);

    TemplateEngine.register("search_result", `
        <button type="button" class="product-card" role="listitem" aria-label="Add {{name}} to cart" data-jscontroller="PosUIController" data-jsclick="@clickAddToCart" data-product='{"id": {{id}}, "name": "{{name}}", "price": {{price}}, "sku": "{{sku}}", "unit": "{{unit}}"}'>
            <div class="product-card-title">{{name}}</div>
            <div class="product-card-price">$\{{price}} / {{unit}}</div>
        </button>
    `);

    TemplateEngine.register("payment_modal", `
        <div class="modal-backdrop" id="pos_payment_modal_backdrop" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: flex; justify-content: center; align-items: center; z-index: 1000;" tabindex="-1">
            <div class="modal-content" style="background: #fff; padding: 2rem; border-radius: 8px; width: 420px; max-width: 95vw; display: flex; flex-direction: column; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                <h3 style="margin-top: 0; margin-bottom: 1rem; flex-shrink: 0;">Seleccionar Método de Pago</h3>
                
                <div style="flex: 1; overflow-y: auto; display: flex; flex-direction: column;">
                    <p style="font-size: 1.1rem; font-weight: bold; margin-bottom: 1.5rem; flex-shrink: 0;" id="payment_modal_total_label" data-total="{{total}}">Total a Pagar: $\{{total}}</p>
                    
                    <div class="payment-methods-list" style="display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 1.5rem; flex-shrink: 0;">
                        {{#payment_methods}}
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 1rem;">
                            <input type="radio" name="selected_payment_method" value="{{id}}" data-code="{{code}}" data-jscontroller="PosUIController" data-jschange="@selectPaymentMethod" {{checked}}>
                            {{name}}
                        </label>
                        {{/payment_methods}}
                    </div>

                    <!-- Cash Section (Conditional view container) -->
                    <div id="payment_cash_section" style="margin-bottom: 1.5rem; display: none; flex-shrink: 0;">
                        <label for="received_amount_input" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Monto Recibido:</label>
                        <input type="number" id="received_amount_input" class="form-control" step="0.01" value="{{total}}" data-jscontroller="PosUIController" data-jsinput="@calculateChange" style="width: 100%; padding: 0.5rem; font-size: 1.1rem;" autofocus>
                        
                        <div style="margin-top: 1rem; font-size: 1.1rem; font-weight: bold;">
                            Cambio: $<span id="change_amount_display" style="color: #059669;">0.00</span>
                        </div>
                    </div>
                </div>

                <div class="modal-actions" style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1rem; flex-shrink: 0;">
                    <button type="button" class="btn btn-secondary" data-jscontroller="PosUIController" data-jsclick="@closePaymentModal" style="padding: 0.5rem 1rem; cursor: pointer;">Cancelar</button>
                    <button type="button" id="confirm_payment_btn" class="btn btn-primary" data-jscontroller="PosUIController" data-jsclick="@confirmCheckout" style="padding: 0.5rem 1.5rem; cursor: pointer; background: #2e7d32; color: #fff; border: none; border-radius: 4px;">Confirmar Pago</button>
                </div>
            </div>
        </div>
    `);

    TemplateEngine.register("scale_modal", `
        <div class="modal-backdrop" id="scale_modal_wrapper" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); display: flex; justify-content: center; align-items: center; z-index: 1050;" tabindex="-1">
            <div class="modal-content" style="background: #fff; padding: 2rem; border-radius: 8px; width: 750px; max-width: 95vw; height: 550px; max-height: 90vh; display: flex; flex-direction: column; box-shadow: 0 8px 24px rgba(0,0,0,0.2);" data-jscontroller="PosUIController">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-shrink: 0;">
                    <h3 style="margin: 0;">Báscula Digital - Selección a Granel</h3>
                    <button type="button" data-jscontroller="PosUIController" data-jsclick="@closeScaleModal" style="background: none; border: none; font-size: 1.25rem; cursor: pointer;">&times;</button>
                </div>
                
                <div style="display: flex; gap: 1rem; margin-bottom: 1rem; flex-shrink: 0;">
                    <input type="text" id="scale_modal_search" class="form-control" placeholder="Buscar producto a granel (ej. queso, jamón)..." data-jskeyup="@handleScaleModalSearch" autocomplete="off" autofocus style="flex: 1; padding: 0.5rem; font-size: 1rem;">
                </div>

                <div id="scale_modal_grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 10px; flex: 1; overflow-y: auto; margin-bottom: 1rem; border: 1px solid #e5e7eb; padding: 10px; border-radius: 6px;">
                    <!-- Filtered scale products render here -->
                </div>

                <div id="scale_modal_readout" style="background: #f9fafb; padding: 1rem; border-radius: 8px; text-align: center; border: 1px solid #e5e7eb; flex-shrink: 0;">
                    <p style="font-size: 1.1rem; color: #4b5563; margin-bottom: 0.5rem;">Producto seleccionado: <strong id="scale_modal_prod_name" style="color: #111;">Ninguno</strong></p>
                    <div style="display: flex; justify-content: center; gap: 2rem; margin: 0.5rem 0;">
                        <div style="background: #111; color: #0f0; font-family: monospace; font-size: 2rem; padding: 0.4rem 1.2rem; border-radius: 6px;">
                            <span id="scale_modal_weight">0.000</span> kg
                        </div>
                        <div style="background: #111; color: #0ff; font-family: monospace; font-size: 2rem; padding: 0.4rem 1.2rem; border-radius: 6px;">
                            $<span id="scale_modal_price">0.00</span>
                        </div>
                    </div>
                </div>

                <div class="modal-actions" style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1rem; flex-shrink: 0;">
                    <button type="button" class="btn btn-secondary" data-jscontroller="PosUIController" data-jsclick="@closeScaleModal" style="padding: 0.5rem 1rem; cursor: pointer;">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="scale_modal_confirm_btn" data-jscontroller="PosUIController" data-jsclick="@confirmScaleItem" disabled style="padding: 0.5rem 1.5rem; cursor: pointer; background: #059669; color: #fff; border: none; border-radius: 4px; opacity: 0.5;">Agregar al Carrito</button>
                </div>
            </div>
        </div>
    `);

    TemplateEngine.register("scale_modal_card", `
        <button type="button" class="product-card" data-jscontroller="PosUIController" data-jsclick="@selectScaleModalProduct" data-product='{"id": {{id}}, "name": "{{name}}", "price": {{price}}, "sku": "{{sku}}", "unit": "{{unit}}", "allows_fraction": true}' style="padding: 10px; text-align: left; background: #fff; border: 1px solid #d1d5db; border-radius: 6px; cursor: pointer;">
            <div style="font-weight: 600; font-size: 0.9rem; color: #1f2937;">{{name}}</div>
            <div style="color: #059669; font-size: 0.85rem; margin-top: 4px;">$\{{price}} / kg</div>
        </button>
    `);

    TemplateEngine.register("cart_item_promotion", `
        <div class="cart-item cart-item--promotion" tabindex="0" data-id="{{id}}" style="background: #f5f3ff; border-left: 4px solid #7c3aed;">
            <div class="cart-item-price">
                {{#if has_original_price}}
                <div style="text-decoration: line-through; font-size: 0.8rem; color: #9ca3af;">{{original_price}}</div>
                {{/if}}
                <div style="color: #7c3aed; font-weight: bold;">{{price}}</div>
            </div>
            <div class="cart-item-details">
                <div class="cart-item-title" style="font-weight: 500; color: #1f2937;">{{name}}</div>
                <div class="cart-item-promo-tag" style="font-size: 0.75rem; color: #7c3aed; font-weight: bold;">🎁 PROMO: {{promotion_name}}</div>
            </div>
            <div class="cart-item-quantity">
                <input type="number" class="cart-item-qty-input" value="{{quantity}}" data-id="{{id}}" data-jscontroller="PosUIController" data-jschange="@changeCartItemQty" min="{{min_qty}}" step="any">
            </div>
            <div class="cart-item-total-col">
                <div class="cart-item-total">{{line_total}}</div>
            </div>
        </div>
    `);

    TemplateEngine.register("promotion_modal", `
        <div class="modal-backdrop" id="promotion_modal_wrapper" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); display: flex; justify-content: center; align-items: center; z-index: 1050;" tabindex="-1">
            <div class="modal-content" style="background: #fff; padding: 2rem; border-radius: 8px; width: 650px; max-width: 95vw; height: 480px; max-height: 90vh; display: flex; flex-direction: column; box-shadow: 0 8px 24px rgba(0,0,0,0.2);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-shrink: 0;">
                    <h3 style="margin: 0;">Seleccionar Promoción Activa</h3>
                    <button type="button" data-jscontroller="PosUIController" data-jsclick="@closePromotionModal" style="background: none; border: none; font-size: 1.25rem; cursor: pointer;">&times;</button>
                </div>
                <div style="margin-bottom: 1rem; flex-shrink: 0;">
                    <input type="text" id="promotion_modal_search" class="form-control" placeholder="Buscar promoción por nombre..." data-jskeyup="@handlePromotionSearch" autocomplete="off" autofocus style="width: 100%; padding: 0.5rem; font-size: 1rem;">
                </div>
                <div id="promotion_modal_grid" style="display: flex; flex-direction: column; gap: 10px; flex: 1; overflow-y: auto; border: 1px solid #e5e7eb; padding: 10px; border-radius: 6px;">
                    <div style="text-align: center; color: #6b7280; padding: 1rem;">Cargando promociones disponibles...</div>
                </div>
            </div>
        </div>
    `);

    TemplateEngine.register("promotion_search_result", `
        <button type="button" class="product-card" data-jscontroller="PosUIController" data-jsclick="@selectPromotion" data-promotion='{{json_data}}' style="padding: 12px; text-align: left; background: #fff; border: 1px solid #d1d5db; border-radius: 6px; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-weight: 600; font-size: 1rem; color: #7c3aed;">{{name}}</div>
                <div style="color: #6b7280; font-size: 0.85rem; margin-top: 4px;">{{description}}</div>
                <div style="margin-top: 6px; font-size: 0.8rem; color: #059669; font-weight: bold;">Precio Promo: $\{{promotional_price}} | Tipo: {{promotion_type}}</div>
            </div>
            <span style="background: #7c3aed; color: white; padding: 4px 10px; border-radius: 4px; font-size: 0.85rem; font-weight: bold;">Aplicar</span>
        </button>
    `);

}, null);

defineModule("PosUIController", ["$", "CSSCore", "AsyncRequest", "TemplateEngine", "KeyboardShortcuts", "GlobalBarcodeScanner", "PosTemplateRegister", "DialogManager", "PosStorageManager"], function (
    global, requireModule, requireDynamic, requireLazy, module, exports, $, CSSCore, AsyncRequest, TemplateEngine, KeyboardShortcuts, Scanner, PosTemplateRegister, DialogManager, PosStorageManager
) {
    'use strict';

    var cart = [];
    var searchTimeout = null;
    var availablePaymentMethods = [];
    var csrfToken = $.fromIDOrElement("csrf_token").getAttribute("content");
    var currentPrintMode = "Ticket";

    var activeScaleProduct = null;
    var scaleStreamSource = null;
    var currentLiveWeightKg = 0;

    var currentCustomer = null;
    var customerSearchTimeout = null;

    var promotionSearchTimeout = null;

    function handleSearchKeydown(event) {
        var resultsContainer = $.fromIDOrElement("pos_product_grid");
        if (!resultsContainer) return;

        var productCards = resultsContainer.querySelectorAll('.product-card');
        if (productCards.length === 0) return;

        var focusedCard = document.activeElement;
        var currentIndex = Array.from(productCards).indexOf(focusedCard);

        if (event.key === 'ArrowDown') {
            event.preventDefault();
            if (currentIndex === -1) {
                // From search input -> go to first card
                productCards[0].focus();
            } else if (currentIndex >= productCards.length - 1) {
                // Reached last card -> wrap to first card
                productCards[0].focus();
            } else {
                productCards[currentIndex + 1].focus();
            }
        } else if (event.key === 'ArrowUp') {
            event.preventDefault();
            if (currentIndex === -1) {
                // From search input -> wrap to last card
                productCards[productCards.length - 1].focus();
            } else if (currentIndex === 0) {
                // Reached first card -> wrap to last card
                productCards[productCards.length - 1].focus();
            } else {
                productCards[currentIndex - 1].focus();
            }
        }
    }

    function handleProductCardKeydown(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            var productStr = event.target.getAttribute('data-product');
            if (productStr) {
                var product = JSON.parse(productStr);
                addToCart(product, null);
                $.fromIDOrElement("pos_product_grid").innerHTML = "";
                clearSearchInput();
            }
        } else if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
            handleSearchKeydown(event);
        }
    }

    // --- PROMOTION MODAL LOGIC ---
    function openPromotionModal() {
        var existing = document.getElementById("promotion_modal_wrapper");
        if (existing) existing.remove();
        
        var modalEl = TemplateEngine.renderToElement("promotion_modal", {});
        document.body.appendChild(modalEl);
        
        DialogManager.open(modalEl, { onClose: function() { clearSearchInput(); } });

        var searchInput = document.getElementById("promotion_modal_search");
        if (searchInput) searchInput.focus();
        
        fetchPromotions("");
    }

    function closePromotionModal() {
        DialogManager.close();
    }

    function handlePromotionSearch(event) {
        var term = event.target.value.trim();
        clearTimeout(promotionSearchTimeout);
        promotionSearchTimeout = setTimeout(function() {
            fetchPromotions(term);
        }, 300);
    }

    function fetchPromotions(term) {
        var grid = document.getElementById("promotion_modal_grid");
        if (!grid) return;

        var req = new AsyncRequest("/admin/pos/promotions/search")
            .setMethod("POST")
            .setRequestHeaders({ "X-CSRF-TOKEN": csrfToken, "Content-Type": "application/json" })
            .setData({ term: term, store_id: 1 });

        req.onSuccess(function(response) {
            var promotions = JSON.parse(response.responseText);
            grid.innerHTML = "";
            if (promotions.length > 0) {
                var processedPromos = promotions.map(function(p) {
                    var copy = Object.assign({}, p);
                    copy.json_data = JSON.stringify(p);
                    return copy;
                });
                TemplateEngine.renderList("promotion_search_result", processedPromos, grid);
            } else {
                grid.innerHTML = '<div style="text-align: center; color: #6b7280; padding: 1rem;">No se encontraron promociones activas</div>';
            }
        });
        req.send();
    }

    function selectPromotion(event, ctrlInstance) {
        var promoData = ctrlInstance.targetElement.getAttribute('data-promotion');
        var promotion = JSON.parse(promoData);

        if (promotion.products && promotion.products.length > 0) {
            var minQty = promotion.minimum_quantity > 0 ? parseFloat(promotion.minimum_quantity) : 1;
            var globalPromoPrice = promotion.promotional_price > 0 ? Number(promotion.promotional_price) : 0;

            promotion.products.forEach(function(prod) {
                var itemPrice = prod.price;
                var displayPrice = globalPromoPrice > 0 ? globalPromoPrice : itemPrice;
                var origPrice = prod.original_price || prod.price || itemPrice;

                var itemObj = {
                    id: prod.id,
                    name: prod.name,
                    sku: prod.sku,
                    barcode: prod.barcode,
                    price: displayPrice,
                    original_price: origPrice,
                    unit: prod.unit,
                    allows_fraction: prod.allows_fraction,
                    is_promotion: true,
                    promotion_id: promotion.id,
                    promotion_name: promotion.name,
                    min_qty: minQty,
                    step_qty: minQty,
                    is_global_package: globalPromoPrice > 0,
                    package_base_price: globalPromoPrice
                };

                addToCart(itemObj, minQty);
            });
        }

        closePromotionModal();
    }

    function openCustomerModal() {
        var existing = document.getElementById("customer_modal_wrapper");
        if (existing) existing.remove();
        
        var modalEl = TemplateEngine.renderToElement("customer_modal", {});
        document.body.appendChild(modalEl);
        
        DialogManager.open(modalEl, { onClose: function() { clearSearchInput(); } });

        var searchInput = document.getElementById("customer_modal_search");
        if (searchInput) searchInput.focus();
    }

    function closeCustomerModal() {
        DialogManager.close();
    }

    function handleCustomerSearch(event) {
        var term = event.target.value.trim();
        clearTimeout(customerSearchTimeout);
        if (term.length < 2) return;

        customerSearchTimeout = setTimeout(function() {
            var grid = document.getElementById("customer_modal_grid");
            var req = new AsyncRequest("/admin/customers/search")
                .setMethod("POST")
                .setRequestHeaders({ "X-CSRF-TOKEN": csrfToken, "Content-Type": "application/json" })
                .setData({ term: term });

            req.onSuccess(function(response) {
                var customers = JSON.parse(response.responseText);
                grid.innerHTML = "";
                if (customers.length > 0) {
                    TemplateEngine.renderList("customer_search_result", customers, grid);
                } else {
                    grid.innerHTML = '<div style="text-align: center; color: #6b7280; padding: 1rem;">No se encontraron clientes</div>';
                }
            });
            req.send();
        }, 300);
    }

    function selectCustomer(event, ctrlInstance) {
        var customerData = ctrlInstance.targetElement.getAttribute('data-customer');
        currentCustomer = JSON.parse(customerData);
        PosStorageManager.saveCustomer(currentCustomer);

        var container = document.getElementById("pos_active_customer_container");
        var nameEl = document.getElementById("pos_active_customer_name");
        
        if (nameEl !== null) {
            nameEl.innerText = "Cliente: " + currentCustomer.name;
            container.style.display = "flex";
        }

        closeCustomerModal();
        
        if (cart.length > 0) {
            var productIds = cart.map(function(item) { return item.id; });
            var req = new AsyncRequest("/admin/recalculate-cart")
                .setMethod("POST")
                .setRequestHeaders({ "X-CSRF-TOKEN": csrfToken, "Content-Type": "application/json" })
                .setData({ customer_id: currentCustomer.id, store_id: 1, items: productIds });

            req.onSuccess(function(response) {
                var updatedPrices = JSON.parse(response.responseText);
                cart.forEach(function(item) {
                    if (updatedPrices[item.id] !== undefined) {
                        item.price = updatedPrices[item.id];
                        var disc = calculateItemDiscount(item, item.quantity);
                        item.discount_amount = disc.amount;
                        item.discount_name = disc.name;
                        item.line_total = Number(((item.quantity * item.price) - disc.amount).toFixed(2));
                    }
                });
                renderCart();
            });
            req.send();
        }
    }   

    function clearCustomer() {
        currentCustomer = null;
        PosStorageManager.saveCustomer(null);
        document.getElementById("pos_active_customer_container").style.display = "none";
        
        if (cart.length > 0) {
            var productIds = cart.map(function(item) { return item.id; });
            var req = new AsyncRequest("/admin/recalculate-cart")
                .setMethod("POST")
                .setRequestHeaders({ "X-CSRF-TOKEN": csrfToken, "Content-Type": "application/json" })
                .setData({ customer_id: null, store_id: 1, items: productIds });

            req.onSuccess(function(response) {
                var updatedPrices = JSON.parse(response.responseText);
                cart.forEach(function(item) {
                    if (updatedPrices[item.id] !== undefined) {
                        item.price = updatedPrices[item.id];
                        item.line_total = Number((item.quantity * item.price).toFixed(2));
                    }
                });
                renderCart();
            });
            req.send();
        }
    }

    function openScaleModal() {
        var existing = document.getElementById("scale_modal_wrapper");
        if (existing) existing.remove();

        var modalEl = TemplateEngine.renderToElement("scale_modal", {});
        document.body.appendChild(modalEl);

        DialogManager.open(modalEl, { onClose: function() {
            if (scaleStreamSource) {
                scaleStreamSource.close();
                scaleStreamSource = null;
            }
            clearSearchInput();
        }});

        activeScaleProduct = null;
        currentLiveWeightKg = 0;

        var searchInput = document.getElementById("scale_modal_search");
        if (searchInput) searchInput.focus();

        initScaleStream();
        fetchBulkProducts("");
    }

    function closeScaleModal() {
        DialogManager.close();
    }

    function handleScaleModalSearch(event) {
        var term = event.target.value.trim();
        fetchBulkProducts(term);
    }

    function fetchBulkProducts(term) {
        var grid = document.getElementById("scale_modal_grid");
        if (!grid) return;

        var req = new AsyncRequest("/admin/pos/search")
            .setMethod("POST")
            .setRequestHeaders({ "X-CSRF-TOKEN": csrfToken, "Content-Type": "application/json" })
            .setData({ term: term, store_id: 1 });

        req.onSuccess(function(response) {
            var products = JSON.parse(response.responseText);
            var bulkProducts = products.filter(function(p) { return p.allows_fraction === true; });
            
            grid.innerHTML = "";
            if (bulkProducts.length > 0) {
                TemplateEngine.renderList("scale_modal_card", bulkProducts, grid);
            } else {
                grid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; color: #6b7280; padding: 1rem;">No se encontraron productos a granel</div>';
            }
        });
        req.send();
    }

    function selectScaleModalProduct(event, ctrlInstance) {
        var productData = ctrlInstance.targetElement.getAttribute('data-product');
        activeScaleProduct = JSON.parse(productData);

        var nameEl = document.getElementById("scale_modal_prod_name");
        if (nameEl) nameEl.innerText = activeScaleProduct.name;

        var confirmBtn = document.getElementById("scale_modal_confirm_btn");
        if (confirmBtn) {
            confirmBtn.disabled = false;
            confirmBtn.style.opacity = "1";
        }
    }

    function initScaleStream() {
        var printerUrl = document.getElementById("printer_url") ? document.getElementById("printer_url").getAttribute("content") : "http://127.0.0.1:3100";
        if (!printerUrl) return;

        scaleStreamSource = new EventSource(printerUrl + "/api/printer/scale/stream");
        scaleStreamSource.onmessage = function(event) {
            var data = JSON.parse(event.data);
            if (data.type === 'weight_update') {
                currentLiveWeightKg = parseFloat(data.weightKg) || 0;
                
                var weightSpan = document.getElementById("scale_modal_weight");
                var priceSpan = document.getElementById("scale_modal_price");

                if (weightSpan) weightSpan.innerText = currentLiveWeightKg.toFixed(3);

                if (priceSpan && activeScaleProduct) {
                    var calcPrice = currentLiveWeightKg * activeScaleProduct.price;
                    priceSpan.innerText = calcPrice.toFixed(2);
                }
            }
        };
    }

    function confirmScaleItem() {
        if (!activeScaleProduct) {
            alert("Seleccione un producto primero.");
            return;
        }

        if (currentLiveWeightKg <= 0) {
            alert("Coloque el producto en la báscula (peso inválido o cero).");
            return;
        }

        addToCart(activeScaleProduct, currentLiveWeightKg);
        closeScaleModal();
    }

    function formatMoney(amount) {
        const numericAmount = Number(amount);
        if (!Number.isFinite(numericAmount)) {
            throw new TypeError(`Invalid monetary amount: ${amount}`);
        }

        return `$${numericAmount.toLocaleString('es-MX', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        })}`;
    }

    function handleModeChange(event, ctrlInstance) {
        var selectElement = event.target;
        if (selectElement) {
            currentPrintMode = selectElement.value;
            PosStorageManager.savePrintMode(currentPrintMode);
        }
    }

    function init(event, ctrlInstance) {
        var storeId = 1;
        fetchPaymentMethods();

        // Restore persisted POS session state
        cart = PosStorageManager.getCart();
        currentCustomer = PosStorageManager.getCustomer();
        currentPrintMode = PosStorageManager.getPrintMode();

        var modeSelect = document.getElementById("ticket_mode_select");
        if (modeSelect) {
            modeSelect.value = currentPrintMode;
        }

        if (currentCustomer) {
            var container = document.getElementById("pos_active_customer_container");
            var nameEl = document.getElementById("pos_active_customer_name");
            if (nameEl !== null) {
                nameEl.innerText = "Cliente: " + currentCustomer.name;
                container.style.display = "flex";
            }
        }

        renderCart();

        KeyboardShortcuts.init();
        KeyboardShortcuts.register('f5', function() {
            if (cart.length > 0) openPaymentModal();
        });

        document.addEventListener('keydown', handleGlobalKeybindings);

        var cartItemsContainer = $.fromIDOrElement("pos_cart_items");
        if (cartItemsContainer) {
            cartItemsContainer.addEventListener('keydown', handleCartKeydown);
        }

        Scanner.onScan(function(barcode) {
            var searchInput = $.fromIDOrElement("pos_search_input");
            if (searchInput) searchInput.value = barcode;
            handleBarcodeScan(barcode, storeId);
        });

        var searchInput = $.fromIDOrElement("pos_search_input");
        if (searchInput) {
            searchInput.addEventListener('keydown', handleSearchKeydown);
        }

        clearSearchInput();
    }

    function handleGlobalKeybindings(e) {

        if (e.key === 'Escape') {
            e.preventDefault();
            var resultsContainer = $.fromIDOrElement("pos_product_grid");
            if (resultsContainer) {
                resultsContainer.innerHTML = "";
            }
            clearSearchInput();
            return;
        }

        if (document.getElementById("pos_dynamic_modal_wrapper") || document.getElementById("scale_modal_wrapper") || document.getElementById("customer_modal_wrapper") || document.getElementById("promotion_modal_wrapper")) return;

        var activeTag = document.activeElement ? document.activeElement.tagName : '';
        var isInputActive = ['INPUT', 'TEXTAREA', 'SELECT'].indexOf(activeTag) !== -1;

        if (!isInputActive) {
            switch (e.key.toLowerCase()) {
                case '1':
                    e.preventDefault();
                    var prodSec = $.fromIDOrElement('pos_cart_section');
                    if (prodSec) prodSec.focus();
                    break;
                case '2':
                    e.preventDefault();
                    var cartSec = $.fromIDOrElement('pos_products_section');
                    if (cartSec) {
                        cartSec.focus();
                        var firstCartItem = document.querySelector('.cart-item');
                        if (firstCartItem) firstCartItem.focus();
                    }
                    break;
                case 'c':
                    e.preventDefault();
                    openCustomerModal();
                    break;
                case 'p':
                    e.preventDefault();
                    openPromotionModal();
                    break;
                case 's':
                    e.preventDefault();
                    clearSearchInput();
                    break;
                case 'm':
                    e.preventDefault();
                    var modeSelect = document.getElementById("ticket_mode_select");
                    if (modeSelect) modeSelect.focus();
                    break;
                case 'b':
                    e.preventDefault();
                    openScaleModal();
                    break;
            }
        }
    }

    function handleCartKeydown(e) {
        var itemEl = e.target.closest('.cart-item');
        if (!itemEl) return;

        var productId = parseInt(itemEl.getAttribute('data-id'), 10);
        var productIndex = cart.findIndex(function(p) { return p.id === productId; });
        if (productIndex === -1) return;

        var isInputFocused = e.target.tagName === 'INPUT';

        switch (e.key) {
            case 'ArrowUp':
                e.preventDefault();
                var prev = itemEl.previousElementSibling;
                if (prev && prev.classList.contains('cart-item')) prev.focus();
                break;
            case 'ArrowDown':
                e.preventDefault();
                var next = itemEl.nextElementSibling;
                if (next && next.classList.contains('cart-item')) next.focus();
                break;
            case 'ArrowRight':
                if (!isInputFocused) {
                    e.preventDefault();
                    var stepRight = cart[productIndex].step_qty || 1;
                    updateCartItemQuantity(productId, cart[productIndex].quantity + stepRight, itemEl);
                }
                break;
            case 'ArrowLeft':
                if (!isInputFocused) {
                    e.preventDefault();
                    var stepLeft = cart[productIndex].step_qty || 1;
                    var currentQty = cart[productIndex].quantity;
                    var newQtyLeft = currentQty - stepLeft;
                    
                    if (newQtyLeft < stepLeft) {
                        removeFromCart(productId);
                    } else {
                        updateCartItemQuantity(productId, newQtyLeft, itemEl);
                    }
                }
                break;
            case 'Backspace':
            case 'Delete':
                if (!isInputFocused) {
                    e.preventDefault();
                    removeFromCart(productId);
                }
                break;
        }
    }

    function clearSearchInput() {
        var searchInput = $.fromIDOrElement("pos_search_input");
        if (searchInput) {
            searchInput.value = "";
            searchInput.focus();
        }
    }

    function handleBarcodeScan(rawBarcode, storeId) {
        var barcode = rawBarcode;
        var parsedWeight = null;

        if (rawBarcode.indexOf('-') > -1) {
            var parts = rawBarcode.split('-');
            barcode = parts[0];
            parsedWeight = parseFloat(parts[1]) / 1000;
        }

        var resultsContainer = $.fromIDOrElement("pos_product_grid");

        var req = new AsyncRequest("/admin/pos/search")
            .setMethod("POST")
            .setRequestHeaders({ "X-CSRF-TOKEN": csrfToken, "Content-Type": "application/json" })
            .setData({ term: barcode, store_id: storeId });

        req.onSuccess(function (response) {
            var products = JSON.parse(response.responseText);
            resultsContainer.innerHTML = "";
            
            // Check if there is an exact primary barcode match or unique SKU match
            var exactMatches = products.filter(function(p) { return p.barcode === barcode || p.sku === barcode; });

            if (exactMatches.length === 1) {
                // Exactly one product matches this barcode uniquely
                var product = exactMatches[0];
                var qty = parsedWeight !== null ? parsedWeight : (product.default_weight !== null ? product.default_weight : 1);
                addToCart(product, qty);
                clearSearchInput();
            } else if (products.length === 1) {
                // Fallback if search only returned a single item total
                var product = products[0];
                var qty = parsedWeight !== null ? parsedWeight : (product.default_weight !== null ? product.default_weight : 1);
                addToCart(product, qty);
                clearSearchInput();
            } else if (products.length > 1) {
                // Multiple items share this barcode (e.g., shared flavor barcodes). 
                // Render them in the search grid so the cashier can choose the correct one.
                TemplateEngine.renderList("search_result", products, resultsContainer);
                var cards = resultsContainer.querySelectorAll('.product-card');
                cards.forEach(function(card) {
                    card.addEventListener('keydown', handleProductCardKeydown);
                });
                
                var searchInput = $.fromIDOrElement("pos_search_input");
                if (searchInput) searchInput.select(); // Highlight text so it's easy to clear/rescan if needed
            } else {
                var notFoundEl = TemplateEngine.renderToElement("product_not_found", { barcode: barcode });
                resultsContainer.appendChild(notFoundEl);
            }
        });
        req.send();
    }

    function fetchPaymentMethods() {
        var req = new AsyncRequest("/admin/pos/payment-methods")
            .setMethod("GET")
            .setRequestHeaders({ "Accept": "application/json" });

        req.onSuccess(function(response) {
            try {
                availablePaymentMethods = JSON.parse(response.responseText);
            } catch (e) {
                console.error("Failed to parse payment methods");
            }
        });
        req.send();
    }

    function handleSearchInput(event, ctrlInstance) {
        var input = event.target;
        var term = input.value.trim();
        var storeId = 1;

        clearTimeout(searchTimeout);

        if (term === "") {
            $.fromIDOrElement("pos_product_grid").innerHTML = "";
            return;
        }

        searchTimeout = setTimeout(function() {
            performSearch(term, storeId);
        }, 250);
    }

    function performSearch(term, storeId) {
        var resultsContainer = $.fromIDOrElement("pos_product_grid");
        if (!resultsContainer) return;

        var req = new AsyncRequest("/admin/pos/search")
            .setMethod("POST")
            .setRequestHeaders({ "X-CSRF-TOKEN": csrfToken, "Content-Type": "application/json" })
            .setData({ 
                term: term, 
                store_id: storeId, 
                customer_id: currentCustomer ? currentCustomer.id : null
            });

        req.onSuccess(function (response) {
            var products = JSON.parse(response.responseText);
            resultsContainer.innerHTML = "";
            
            if (products.length > 0) {
                TemplateEngine.renderList("search_result", products, resultsContainer);
                // Attach keydown listener to each rendered product card
                var cards = resultsContainer.querySelectorAll('.product-card');
                cards.forEach(function(card) {
                    card.addEventListener('keydown', handleProductCardKeydown);
                });
            } else {
                var notFoundEl = TemplateEngine.renderToElement("product_not_found", { barcode: term });
                resultsContainer.appendChild(notFoundEl);
            }
        });
        req.send();
    }

    function clickAddToCart(event, ctrlInstance) {
        var productStr = ctrlInstance.targetElement.getAttribute('data-product');
        var product = JSON.parse(productStr);
        
        addToCart(product, null);
        
        $.fromIDOrElement("pos_product_grid").innerHTML = "";
        clearSearchInput();
    }

    function calculateItemDiscount(product, quantity) {
        if (!product.quantity_rules || product.quantity_rules.length === 0) {
            return { amount: 0, name: null };
        }

        var applicableRules = product.quantity_rules.filter(function(r) {
            return quantity >= parseFloat(r.minimum_quantity);
        });

        if (applicableRules.length === 0) {
            return { amount: 0, name: null };
        }

        applicableRules.sort(function(a, b) {
            if (b.priority !== a.priority) return b.priority - a.priority;
            return parseFloat(b.minimum_quantity) - parseFloat(a.minimum_quantity);
        });

        var rule = applicableRules[0];
        var unitPrice = product.price;
        var timesToApply = 1;
        var groupQty = rule.group_quantity > 0 ? parseFloat(rule.group_quantity) : parseFloat(rule.minimum_quantity);

        if (rule.repeatable) {
            timesToApply = Math.floor(quantity / groupQty);
        }

        if (timesToApply <= 0) {
            return { amount: 0, name: null };
        }

        var affectedQty = timesToApply * groupQty;
        var discountAmount = 0;

        if (rule.rule_type === 'percentage') {
            discountAmount = (unitPrice * affectedQty) * (parseFloat(rule.discount_value) / 100);
        } else if (rule.rule_type === 'unit_discount') {
            discountAmount = parseFloat(rule.discount_value) * affectedQty;
        } else if (rule.rule_type === 'fixed_price') {
            var standardPrice = affectedQty * unitPrice;
            var discountedPrice = timesToApply * parseFloat(rule.discount_value);
            discountAmount = Math.max(0, standardPrice - discountedPrice);
        }

        return {
            amount: Number(discountAmount.toFixed(2)),
            name: rule.name || 'Descuento Promocional'
        };
    }

    function addToCart(product, forceQuantity) {
        var existingIndex = cart.findIndex(function(item) { return item.id === product.id; });
        var qtyToAdd = (forceQuantity !== undefined && forceQuantity !== null) ? forceQuantity : 1;

        if (existingIndex > -1) {
            cart[existingIndex].quantity += qtyToAdd;
            var item = cart[existingIndex];
            
            if (item.is_global_package && item.min_qty > 0) {
                var multiplier = Math.ceil(item.quantity / item.min_qty);
                item.line_total = Number((item.package_base_price * multiplier).toFixed(2));
            } else {
                var disc = calculateItemDiscount(item, item.quantity);
                item.discount_amount = disc.amount;
                item.discount_name = disc.name;
                item.line_total = Number(((item.quantity * item.price) - disc.amount).toFixed(2));
            }
        } else {
            product.quantity = qtyToAdd;
            product.discount_amount = 0;
            product.discount_name = null;

            if (product.is_global_package && product.min_qty > 0) {
                var multiplier = Math.ceil(product.quantity / product.min_qty);
                product.line_total = Number((product.package_base_price * multiplier).toFixed(2));
            } else {
                var disc = calculateItemDiscount(product, qtyToAdd);
                product.discount_amount = disc.amount;
                product.discount_name = disc.name;
                product.line_total = Number(((product.price * qtyToAdd) - disc.amount).toFixed(2));
            }
            cart.push(product);
        }

        PosStorageManager.saveCart(cart);
        renderCart();
    }

    function renderCart() {
        var container = $.fromIDOrElement("pos_cart_items");
        var subtotalEl = $.fromIDOrElement("cart_subtotal");
        var totalEl = $.fromIDOrElement("cart_total");

        if (!container) return;
        container.innerHTML = "";

        if (cart.length === 0) {
            container.innerHTML = '<div class="empty-cart-message">Escanea o busca un producto para comenzar</div>';
            if (subtotalEl) subtotalEl.innerText = formatMoney(0);
            if (totalEl) totalEl.innerText = formatMoney(0);
            return;
        }

        var total = 0;
        cart.forEach(function(item) {
            total += item.line_total;
            var copy = Object.assign({}, item);
            copy.price = formatMoney(item.price);
            copy.original_price = formatMoney(item.original_price || item.price);
            copy.has_original_price = item.original_price && item.original_price > item.price;
            copy.line_total = formatMoney(item.line_total);
            
            var discountAmount = Number(item.discount_amount || 0);
            copy.has_discount = discountAmount > 0;
            copy.formatted_discount = formatMoney(discountAmount);
            copy.min_qty = item.min_qty || (item.allows_fraction ? 0.001 : 1);

            var templateName = item.is_promotion ? "cart_item_promotion" : "cart_item";
            var itemEl = TemplateEngine.renderToElement(templateName, copy);
            container.appendChild(itemEl);
        });

        var formattedTotal = formatMoney(total);
        if (subtotalEl) subtotalEl.innerText = formattedTotal;
        if (totalEl) totalEl.innerText = formattedTotal;
    }

    function updateTotalsSummary() {
        var subtotalEl = $.fromIDOrElement("cart_subtotal");
        var totalEl = $.fromIDOrElement("cart_total");

        var total = cart.reduce(function(sum, item) { return sum + item.line_total; }, 0);
        var formattedTotal = formatMoney(total);

        if (subtotalEl) subtotalEl.innerText = formattedTotal;
        if (totalEl) totalEl.innerText = formattedTotal;
    }

    function calculateChange() {
        var totalLabel = document.getElementById("payment_modal_total_label");
        var receivedInput = document.getElementById("received_amount_input");
        var changeDisplay = document.getElementById("change_amount_display");
        var confirmBtn = document.getElementById("confirm_payment_btn");

        if (!totalLabel || !receivedInput || !changeDisplay) return;

        var total = parseFloat(totalLabel.getAttribute("data-total")) || 0;
        var received = parseFloat(receivedInput.value) || 0;
        var change = received - total;

        if (received < total) {
            changeDisplay.style.color = "red";
            changeDisplay.innerText = change.toFixed(2);
            if (confirmBtn) {
                confirmBtn.disabled = true;
                confirmBtn.style.opacity = "0.5";
                confirmBtn.style.cursor = "not-allowed";
            }
        } else {
            changeDisplay.style.color = "#059669";
            changeDisplay.innerText = change.toFixed(2);
            if (confirmBtn) {
                confirmBtn.disabled = false;
                confirmBtn.style.opacity = "1";
                confirmBtn.style.cursor = "pointer";
            }
        }
    }

    function changeCartItemQty(event, ctrlInstance) {
        var input = event.target;
        var productId = parseInt(input.getAttribute('data-id'), 10);
        
        var newQty = parseFloat(input.value);
        if (isNaN(newQty) || newQty <= 0) newQty = 1;
        
        var itemEl = input.closest('.cart-item');
        updateCartItemQuantity(productId, newQty, itemEl);
    }

    function updateCartItemQuantity(productId, newQty, itemEl) {
        var index = cart.findIndex(function(item) { return item.id === productId; });
        if (index > -1) {
            cart[index].quantity = Number(newQty);
            var item = cart[index];

            if (item.is_global_package && item.min_qty > 0) {
                var multiplier = Math.ceil(item.quantity / item.min_qty);
                item.line_total = Number((item.package_base_price * multiplier).toFixed(2));
            } else {
                var disc = calculateItemDiscount(item, item.quantity);
                item.discount_amount = disc.amount;
                item.discount_name = disc.name;
                item.line_total = Number(((item.quantity * Number(item.price)) - disc.amount).toFixed(2));
            }
            
            PosStorageManager.saveCart(cart);

            if (itemEl) {
                var inputEl = itemEl.querySelector('.cart-item-qty-input');
                if (inputEl && document.activeElement !== inputEl) {
                    inputEl.value = cart[index].quantity;
                }
            }

            var totalEl = itemEl ? itemEl.querySelector('.cart-item-total') : null;
            if (totalEl) {
                totalEl.innerText = formatMoney(cart[index].line_total);
                updateTotalsSummary();
            } else {
                renderCart();
                var refreshedEl = document.querySelector('.cart-item[data-id="' + productId + '"]');
                if (refreshedEl) {
                    var input = refreshedEl.querySelector('.cart-item-qty-input');
                    if (input) {
                        input.value = cart[index].quantity;
                        input.focus();
                        input.setSelectionRange(input.value.length, input.value.length);
                    }
                }
            }
        }
    }

    function removeFromCart(productId) {
        cart = cart.filter(function(item) { return item.id !== productId; });
        PosStorageManager.saveCart(cart);
        renderCart();
        var cartSec = $.fromIDOrElement('pos_cart_section');
        if (cartSec) cartSec.focus();
    }

    function clearCart() {
        cart = [];
        PosStorageManager.clearSession();
        renderCart();
        clearSearchInput();
    }

    /* ==========================
     * MODAL LOGIC (Payment Modal)
     * ========================== */
    function openPaymentModal(event, ctrlInstance) {
        if (cart.length === 0) return;

        var existingModal = document.getElementById("pos_dynamic_modal_wrapper");
        if (existingModal) existingModal.remove();

        var total = cart.reduce(function(sum, item) { return sum + item.line_total; }, 0);

        if (!availablePaymentMethods || availablePaymentMethods.length === 0) {
            availablePaymentMethods = [{ id: 1, code: 'cash', name: 'Efectivo' }];
        }

        var processedMethods = availablePaymentMethods.map(function(method, index) {
            var copy = Object.assign({}, method);
            copy.checked = (index === 0) ? 'checked' : '';
            return copy;
        });

        var modalElement = TemplateEngine.renderToElement("payment_modal", {
            total: total.toFixed(2),
            payment_methods: processedMethods
        });

        modalElement.id = "pos_dynamic_modal_wrapper";
        document.body.appendChild(modalElement);

        DialogManager.open(modalElement, {
            onClose: function() { clearSearchInput(); }
        });

        updatePaymentFieldsVisibility();
    }

    function updatePaymentFieldsVisibility() {
        var checkedRadio = document.querySelector('input[name="selected_payment_method"]:checked');
        var cashSection = document.getElementById("payment_cash_section");

        if (!checkedRadio || !cashSection) return;

        var code = checkedRadio.getAttribute("data-code");
        if (code === "cash") {
            cashSection.style.display = "block";
            var receivedInput = document.getElementById("received_amount_input");
            if (receivedInput) {
                receivedInput.focus();
                receivedInput.select();
                calculateChange(); // Compute initial change on modal open
            }
        } else {
            cashSection.style.display = "none";
        }
    }

    function closePaymentModal() {
        DialogManager.close();
    }

    function confirmCheckout(event, ctrlInstance) {
        var checkedRadio = document.querySelector('input[name="selected_payment_method"]:checked');
        if (!checkedRadio) {
            alert("Por favor seleccione un método de pago.");
            return;
        }

        var confirmBtn = event.target;
        var defaultConfirmBtnText = confirmBtn.innerText;

        var paymentMethodId = parseInt(checkedRadio.value, 10);
        var paymentCode = checkedRadio.getAttribute("data-code");
        var total = cart.reduce(function(sum, item) { return sum + item.line_total; }, 0);
        var receivedAmount = total;

        if (paymentCode === "cash") {
            var receivedInput = document.getElementById("received_amount_input");
            receivedAmount = receivedInput ? parseFloat(receivedInput.value) || 0 : total;

            if (receivedAmount < total) {
                return;
            }
        }

        var payload = {
            store_id: 1,
            terminal_id: 1,
            customer_id: currentCustomer ? currentCustomer.id : null,
            items: cart.map(function(item) { return { product_id: item.id, quantity: item.quantity }; }),
            payments: [{
                payment_method_id: paymentMethodId,
                amount: total,
                received_amount: receivedAmount
            }],
            mode: currentPrintMode 
        };

        confirmBtn.innerText = 'Procesando pago...';
        confirmBtn.disabled = true;

        var req = new AsyncRequest("/admin/pos/checkout")
            .setMethod("POST")
            .setRequestHeaders({ "X-CSRF-TOKEN": csrfToken, "Content-Type": "application/json", "Accept": "application/json" })
            .setData(JSON.stringify(payload));

        req.onSuccess(function(response) {
            try {
                var data = JSON.parse(response.responseText);
                if (data.success) {
                    confirmBtn.innerText = defaultConfirmBtnText;
                    confirmBtn.disabled = false;
                    alert("¡Venta completada! Ticket: " + data.ticket_number);
                    closePaymentModal();
                    clearCart();
                } else {
                    confirmBtn.disabled = false;
                    alert("Error en el cobro: " + (data.message || "Error desconocido"));
                }
            } catch (err) {
                confirmBtn.disabled = false;
                alert("El servidor devolvió un formato de respuesta inválido.");
            }
        });

        req.onError(function(xhr) {
            try {
                var errData = JSON.parse(xhr.responseText);
                confirmBtn.disabled = false;
                alert("Fallo el cobro: " + (errData.message || "Revise los datos."));
            } catch (e) {
                alert("Ocurrió un error en el servidor durante el cobro.");
            }
        });

        req.send();
    }

    exports.init = init;
    exports.handleSearchInput = handleSearchInput;
    exports.clickAddToCart = clickAddToCart;
    exports.clearCart = clearCart;
    exports.openPaymentModal = openPaymentModal;
    exports.selectPaymentMethod = updatePaymentFieldsVisibility;
    exports.calculateChange = calculateChange;
    exports.closePaymentModal = closePaymentModal;
    exports.confirmCheckout = confirmCheckout;
    exports.changeCartItemQty = changeCartItemQty;
    exports.handleModeChange = handleModeChange;

    exports.openScaleModal = openScaleModal;
    exports.closeScaleModal = closeScaleModal;
    exports.handleScaleModalSearch = handleScaleModalSearch;
    exports.selectScaleModalProduct = selectScaleModalProduct;
    exports.confirmScaleItem = confirmScaleItem;

    exports.openCustomerModal = openCustomerModal;
    exports.closeCustomerModal = closeCustomerModal;
    exports.handleCustomerSearch = handleCustomerSearch;
    exports.selectCustomer = selectCustomer;
    exports.clearCustomer = clearCustomer;

    exports.openPromotionModal = openPromotionModal;
    exports.closePromotionModal = closePromotionModal;
    exports.handlePromotionSearch = handlePromotionSearch;
    exports.selectPromotion = selectPromotion;

}, null);
