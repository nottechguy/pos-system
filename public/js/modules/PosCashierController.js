defineModule("POSCashierController", ["$", "AsyncRequest", "TemplateEngine", "DialogManager"], function (
    global, requireModule, requireDynamic, requireLazy, module, exports, $, AsyncRequest, TemplateEngine, DialogManager
) {
    'use strict';

    var csrfToken = $.fromIDOrElement("csrf_token") ? $.fromIDOrElement("csrf_token").getAttribute("content") : "";
    var previousSelectedId = null;

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