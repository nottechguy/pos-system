defineModule("POSCashierController", ["$", "AsyncRequest", "TemplateEngine", "DialogManager"], function (
    global, requireModule, requireDynamic, requireLazy, module, exports, $, AsyncRequest, TemplateEngine, DialogManager
) {
    'use strict';

    var csrfToken = $.fromIDOrElement("csrf_token") ? $.fromIDOrElement("csrf_token").getAttribute("content") : "";
    var previousSelectedId = null;

    // Register modal template inside the cashier module
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

    function init(event, ctrlInstance) {
        fetchCashiersList();
    }

    function fetchCashiersList() {
        var selector = document.getElementById("pos_cashier_selector");
        if (!selector) return;

        var req = new AsyncRequest("/admin/pos/cashier/list")
            .setMethod("GET")
            .setRequestHeaders({ "Accept": "application/json" });

        req.onSuccess(function(response) {
            try {
                var cashiers = JSON.parse(response.responseText);
                selector.innerHTML = "";

                cashiers.forEach(function(c) {
                    var opt = document.createElement("option");
                    opt.value = c.id;
                    opt.textContent = c.name;
                    if (c.is_current) {
                        opt.selected = true;
                        previousSelectedId = c.id;
                    }
                    selector.appendChild(opt);
                });
            } catch (e) {
                console.error("Failed to parse cashiers list");
            }
        });
        req.send();
    }

    function onCashierSelect(event, ctrlInstance) {
        var selector = event.target;
        var selectedId = parseInt(selector.value, 10);
        var selectedName = selector.options[selector.selectedIndex].text;

        // If selecting current user, do nothing
        if (selectedId === previousSelectedId) {
            return;
        }

        // Open authorization modal prompting for PIN/password
        openAuthModal(selectedId, selectedName);
    }

    function openAuthModal(cashierId, cashierName) {
        var existing = document.getElementById("cashier_auth_modal_wrapper");
        if (existing) existing.remove();

        var modalEl = TemplateEngine.renderToElement("cashier_auth_modal", {
            cashier_id: cashierId,
            cashier_name: cashierName
        });

        document.body.appendChild(modalEl);
        DialogManager.open(modalEl, {
            onClose: function() {
                // Revert select dropdown back to previous active cashier if cancelled
                var selector = document.getElementById("pos_cashier_selector");
                if (selector && previousSelectedId) {
                    selector.value = previousSelectedId;
                }
            }
        });

        var input = document.getElementById("cashier_credentials_input");
        if (input) input.focus();
    }

    function closeAuthModal() {
        DialogManager.close();
    }

    function confirmSwitchCashier(event, ctrlInstance) {
        event.preventDefault();

        var cashierId = document.getElementById("auth_target_cashier_id").value;
        var credentialsInput = document.getElementById("cashier_credentials_input");
        var errorSpan = document.getElementById("cashier_auth_error");
        var credentials = credentialsInput ? credentialsInput.value : "";

        if (errorSpan) errorSpan.style.display = "none";

        var req = new AsyncRequest("/admin/pos/cashier/switch")
            .setMethod("POST")
            .setRequestHeaders({ "X-CSRF-TOKEN": csrfToken, "Content-Type": "application/json", "Accept": "application/json" })
            .setData(JSON.stringify({ user_id: cashierId, credentials: credentials }));

        req.onSuccess(function(response) {
            try {
                var data = JSON.parse(response.responseText);
                if (data.success) {
                    previousSelectedId = parseInt(cashierId, 10);
                    closeAuthModal();
                    
                    // Optional: Update avatar dynamically without full page reload if desired,
                    // or reload to refresh full POS context state:
                    window.location.reload();
                }
            } catch (err) {
                if (errorSpan) {
                    errorSpan.innerText = "Error procesando la respuesta.";
                    errorSpan.style.display = "block";
                }
            }
        });

        req.onError(function(xhr) {
            if (errorSpan) {
                try {
                    var errData = JSON.parse(xhr.responseText);
                    errorSpan.innerText = errData.message || "Credenciales incorrectas.";
                } catch (e) {
                    errorSpan.innerText = "Contraseña o PIN incorrecto.";
                }
                errorSpan.style.display = "block";
            }
        });

        req.send();
    }

    exports.init = init;
    exports.onCashierSelect = onCashierSelect;
    exports.closeAuthModal = closeAuthModal;
    exports.confirmSwitchCashier = confirmSwitchCashier;

}, null);