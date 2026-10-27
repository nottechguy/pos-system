defineModule("LabelPrinterController", ["$", "AsyncRequest", "TemplateEngine"], function (
    global, requireModule, requireDynamic, requireLazy, module, exports, $, AsyncRequest, TemplateEngine
) {
    'use strict';

    TemplateEngine.register("printer_product_result", `
        <button type="button" class="product-card" data-jscontroller="LabelPrinterController" data-jsclick="@selectProduct" data-jsparams='{"id": {{id}}, "name": "{{name}}", "barcode": "{{barcode}}", "sale_price": {{sale_price}}, "allows_fractional": {{allows_fractional_qty}}, "sale_unit_code": "{{sale_unit_code}}"}' style="text-align: left; padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px; background: #fff; cursor: pointer; transition: all 0.2s;">
            <div class="product-card-title" style="font-weight: bold; color: var(--text-main);">{{name}}</div>
            <div class="product-card-price" style="color: var(--primary); margin-top: 4px;">$<span style="font-weight: bold;">{{sale_price}}</span></div>
            <div class="product-card-barcode" style="font-size: 0.85rem; color: var(--text-muted); margin-top: 2px;">{{barcode}}</div>
        </button>
    `);

    var searchTimeout = null;
    var csrfToken = $.fromIDOrElement("csrf_token").getAttribute("content");
    var activeProduct = null;

    function init(event, ctrlInstance) {
        triggerSearch();
    }

    function handleSearch(event, ctrlInstance) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(triggerSearch, 250);
    }

    function triggerSearch() {
        var term = $.fromIDOrElement("printer_search_input").value.trim();
        var deptId = $.fromIDOrElement("printer_dept_select").value;
        var resultsContainer = $.fromIDOrElement("printer_product_grid");

        var req = new AsyncRequest("/admin/label-printer/search")
            .setMethod("POST")
            .setRequestHeaders({ "X-CSRF-TOKEN": csrfToken, "Content-Type": "application/json" })
            .setData({ term: term, department_id: deptId });

        req.onSuccess(function (response) {
            var products = JSON.parse(response.responseText);
            resultsContainer.innerHTML = "";
            TemplateEngine.renderList("printer_product_result", products, resultsContainer);
        });

        req.send();
    }

    function selectProduct(event, ctrlInstance) {
        activeProduct = {
            id: ctrlInstance.getParam("id"),
            name: ctrlInstance.getParam("name"),
            barcode: ctrlInstance.getParam("barcode"),
            sale_price: ctrlInstance.getParam("sale_price"),
            allows_fractional: ctrlInstance.getParam("allows_fractional"),
            sale_unit_code: ctrlInstance.getParam("sale_unit_code")
        };

        // Populate configuration panel details
        $.fromIDOrElement("selected_product_name").innerText = activeProduct.name;
        $.fromIDOrElement("selected_product_barcode").innerText = activeProduct.barcode;
        $.fromIDOrElement("selected_product_price").innerText = parseFloat(activeProduct.sale_price).toFixed(2);
        
        // Dynamic Input Adjustments based on Unit & Fractional Status
        var labelEl = $.fromIDOrElement("print_quantity_label");
        var helpEl = $.fromIDOrElement("print_quantity_help");
        var inputEl = $.fromIDOrElement("print_weight");

        if (activeProduct.allows_fractional || activeProduct.sale_unit_code === 'kilogram') {
            labelEl.innerText = "Peso (kg) *";
            helpEl.innerText = "Ingrese el peso exacto en kilogramos (ej. 0.162 para 162g).";
            inputEl.step = "0.001";
            inputEl.min = "0.001";
            inputEl.value = "1.000";
        } else {
            labelEl.innerText = "Cantidad de Piezas *";
            helpEl.innerText = "Ingrese el número de piezas a etiquetar.";
            inputEl.step = "1";
            inputEl.min = "1";
            inputEl.value = "1";
        }

        $.fromIDOrElement("print_copies").value = "1";

        // Hide search section & grid, clear search input, show config panel
        $.fromIDOrElement("printer_search_section").style.display = "none";
        $.fromIDOrElement("printer_search_input").value = "";
        $.fromIDOrElement("printer_product_grid").innerHTML = "";
        $.fromIDOrElement("printer_config_panel").style.display = "block";

        inputEl.focus();
    }

    function cancelSelection(event, ctrlInstance) {
        activeProduct = null;
        
        // Hide config panel, show search section again
        $.fromIDOrElement("printer_config_panel").style.display = "none";
        $.fromIDOrElement("printer_search_section").style.display = "block";
        
        triggerSearch();
        $.fromIDOrElement("printer_search_input").focus();
    }

    function executePrint(event, ctrlInstance) {
        if (!activeProduct) {
            alert("Por favor seleccione un producto primero.");
            return;
        }

        var weight = parseFloat($.fromIDOrElement("print_weight").value) || 1.000;
        var copies = parseInt($.fromIDOrElement("print_copies").value) || 1;

        var payload = {
            product_id: activeProduct.id,
            weight: weight,
            copies: copies
        };

        var req = new AsyncRequest("/admin/label-printer/print")
            .setMethod("POST")
            .setRequestHeaders({ "X-CSRF-TOKEN": csrfToken, "Content-Type": "application/json" })
            .setData(JSON.stringify(payload));

        req.onSuccess(function(response) {
            var res = JSON.parse(response.responseText);
            alert(res.message);
            // Reset copies but keep product selected for batch label printing
            $.fromIDOrElement("print_copies").value = "1";
            $.fromIDOrElement("print_weight").focus();
        });

        req.onError(function(xhr) {
            var res = JSON.parse(xhr.responseText || "{}");
            alert(res.message || "Error al enviar la etiqueta a impresión.");
        });

        req.send();
    }

    init();

    exports.init = init;
    exports.handleSearch = handleSearch;
    exports.selectProduct = selectProduct;
    exports.cancelSelection = cancelSelection;
    exports.executePrint = executePrint;
}, null);