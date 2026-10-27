defineModule("ScaleUIController", ["$", "AsyncRequest", "TemplateEngine"], function (
    global, requireModule, requireDynamic, requireLazy, module, exports, $, AsyncRequest, TemplateEngine
) {
    'use strict';

    // 1. Register the template directly in the JS module (Matching your architecture)
    TemplateEngine.register("scale_result", `
        <button type="button" class="product-card" data-jscontroller="ScaleUIController" data-jsclick="@selectProduct" data-jsparams='{"name": "{{name}}", "barcode": "{{barcode}}", "sale_price" : {{sale_price}}}'>
            <div class="product-card-title">{{name}}</div>
            <div class="product-card-price">$<span>{{sale_price}}</span></div>
            <div class="product-card-barcode">{{barcode}}</div>
        </button>
    `);

    var searchTimeout = null;
    var csrfToken = $.fromIDOrElement("csrf_token").getAttribute("content");
    var printerUrl = $.fromIDOrElement("printer_url").getAttribute("content");

    var scaleEventSource = null;

    function init(event, ctrlInstance) {
        // Load initial product list
        triggerSearch();
        
        // Initialize the live weight stream
        connectToScaleStream();
    }

    function handleSearch(event, ctrlInstance) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(triggerSearch, 250);
    }

    function triggerSearch() {
        var term = $.fromIDOrElement("scale_search_input").value.trim();
        var deptId = $.fromIDOrElement("scale_dept_select").value;
        var resultsContainer = $.fromIDOrElement("scale_product_grid");

        var req = new AsyncRequest("/admin/scale/search")
            .setMethod("POST")
            .setRequestHeaders({ "X-CSRF-TOKEN": csrfToken, "Content-Type": "application/json" })
            .setData({ term: term, department_id: deptId });

        req.onSuccess(function (response) {
            var products = JSON.parse(response.responseText);
            resultsContainer.innerHTML = "";

            TemplateEngine.renderList("scale_result", products, resultsContainer);
        });

        req.send();
    }

    function selectProduct(event, ctrlInstance) {
        var product = {
            name: ctrlInstance.getParam("name"),
            barcode: ctrlInstance.getParam("barcode"),
            sale_price: ctrlInstance.getParam("sale_price")
        };
        
        armScaleHardware(product);
    }

    var currentArmedPrice = 0;

    function armScaleHardware(product) {
        console.log("Armed Product Data:", product);

        currentArmedPrice = parseFloat(product.sale_price) || 0;
        
        var payload = {
            productName: product.name,
            barcode: product.barcode,
            copies: 1
        };

        var req = new AsyncRequest(printerUrl + "/api/printer/scale/prepare")
            .setMethod("POST")
            .setRequestHeaders({ "Content-Type": "application/json" })
            .setData(JSON.stringify(payload)); 

        req.onSuccess(function(response) {
            $.fromIDOrElement("scale_idle_state").style.display = "none";
            $.fromIDOrElement("scale_armed_state").style.display = "block";
            
            $.fromIDOrElement("armed_product_name").innerText = product.name;
            $.fromIDOrElement("armed_product_barcode").innerText = product.barcode;
            $.fromIDOrElement("armed_product_price").innerText = currentArmedPrice.toFixed(2);
            
            // Reset live displays to 0 initially
            document.querySelectorAll(".live_weight_display").forEach(function(el) { el.innerText = "0.000"; });
            document.querySelectorAll(".live_price_display").forEach(function(el) { el.innerText = "0.00"; });
        });

        req.onError(function(xhr) {
            alert("Error communicating with Scale Server. Ensure Node.js server is running.");
        });

        req.send();
    }

    function connectToScaleStream() {
        if (!printerUrl) return;

        scaleEventSource = new EventSource(printerUrl + "/api/printer/scale/stream");

        scaleEventSource.onmessage = function(event) {
            var data = JSON.parse(event.data);
            
            if (data.type === 'weight_update') {
                var weightVal = parseFloat(data.weightKg);
                var calculatedPrice = weightVal * currentArmedPrice;
                
                var weightDisplays = document.querySelectorAll(".live_weight_display");
                weightDisplays.forEach(function(el) {
                    el.innerText = weightVal.toFixed(3);
                });

                var priceDisplays = document.querySelectorAll(".live_price_display");
                priceDisplays.forEach(function(el) {
                    el.innerText = calculatedPrice.toFixed(2);
                });
            }
        };

        scaleEventSource.onerror = function(error) {
            console.error("Lost connection to scale stream. Reconnecting...", error);
        };
    }

    function cancelArmedState(event, ctrlInstance) {
        var req = new AsyncRequest(printerUrl + "/api/printer/scale/prepare")
            .setMethod("DELETE");

        req.onSuccess(function() {
            $.fromIDOrElement("scale_idle_state").style.display = "block";
            $.fromIDOrElement("scale_armed_state").style.display = "none";
            $.fromIDOrElement("scale_search_input").focus();
        });
        
        req.send();
    }

    function printFromUI(event, ctrlInstance) {
        var req = new AsyncRequest(printerUrl + "/api/printer/scale/print-current")
            .setMethod("POST")
            .setRequestHeaders({ "Content-Type": "application/json" });

        req.onSuccess(function(response) {
            // Give subtle visual feedback (e.g., temporary button label change) without resetting armed state
            var printBtn = event.target;
            var originalText = printBtn.innerText;
            printBtn.innerText = "¡Etiqueta Impresa! 🖨️";
            printBtn.style.backgroundColor = "#059669";
            
            setTimeout(function() {
                printBtn.innerText = originalText;
                printBtn.style.backgroundColor = "";
            }, 1200);
        });

        req.onError(function(xhr) {
            var res = JSON.parse(xhr.responseText || "{}");
            alert(res.message || "Failed to print label. Ensure weight is on the scale.");
        });

        req.send();
    }

    init();

    exports.init = init;
    exports.handleSearch = handleSearch;
    exports.selectProduct = selectProduct;
    exports.cancelArmedState = cancelArmedState;
    exports.printFromUI = printFromUI;
}, null);