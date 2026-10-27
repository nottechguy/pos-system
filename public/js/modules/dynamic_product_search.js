defineModule("DynamicProductSearchRegister", ["TemplateEngine"], function (global, requireModule, requireDynamic, requireLazy, module, exports, TemplateEngine) {
    'use strict';
    TemplateEngine.register("dps_result_item", `
        <div class="dps-item" data-jscontroller="DynamicProductSearch" data-jsclick="@selectItem" data-id="{{id}}" data-name="{{name}}" data-sku="{{sku}}" data-unit="{{unit}}" data-price="{{price}}" style="padding: 10px 12px; cursor: pointer; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; flex-direction: column;">
                <strong style="color: #1f2937; font-size: 0.95rem;">{{name}}</strong>
                <span style="color: #6b7280; font-size: 0.8rem;">SKU: {{sku}} | Unidad: {{unit}}</span>
            </div>
            <div style="color: #059669; font-weight: 600; font-size: 0.95rem;">
                $\{{price}}
            </div>
        </div>
    `);
}, null);

defineModule("DynamicProductSearch", ["$", "AsyncRequest", "TemplateEngine", "DynamicProductSearchRegister"], function (global, requireModule, requireDynamic, requireLazy, module, exports, $, AsyncRequest, TemplateEngine, DPSRegister) {
    'use strict';

    var searchTimeouts = {};
    var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") || '';
    var onProductSelectedCallback = null;

    function handleInput(event, ctrlInstance) {
        var container = event.target.closest('.dynamic-product-search');
        var id = container.id;
        
        clearTimeout(searchTimeouts[id]);
        searchTimeouts[id] = setTimeout(function() {
            triggerSearch(container);
        }, 300);
    }

    function handleFocus(event, ctrlInstance) {
        var container = event.target.closest('.dynamic-product-search');
        var dropdown = container.querySelector('[data-jsbind="dropdown"]');
        dropdown.style.display = 'block';
        if (event.target.value.trim().length >= 0) {
             triggerSearch(container);
        }
    }

    function handleFilterChange(event, ctrlInstance) {
        var container = event.target.closest('.dynamic-product-search');
        triggerSearch(container);
    }

    function triggerSearch(container) {
        var endpoint = container.getAttribute('data-endpoint');
        var excludeId = container.getAttribute('data-exclude');
        var dropdown = container.querySelector('[data-jsbind="dropdown"]');
        var term = container.querySelector('[data-jsbind="search_input"]').value.trim();
        
        var deptId = container.querySelector('[data-jsbind="filter_department"]').value;
        var catId = container.querySelector('[data-jsbind="filter_category"]').value;
        var fractional = container.querySelector('[data-jsbind="filter_fractional"]').checked ? 1 : '';
        var active = container.querySelector('[data-jsbind="filter_active"]').checked ? 1 : '';

        var req = new AsyncRequest(endpoint)
            .setMethod("POST")
            .setRequestHeaders({ "X-CSRF-TOKEN": csrfToken, "Content-Type": "application/json" })
            .setData({
                term: term,
                department_id: deptId,
                category_id: catId,
                allows_fractional_qty: fractional,
                is_active: active,
                exclude_id: excludeId
            });

        req.onSuccess(function(response) {
            var products = JSON.parse(response.responseText);
            dropdown.innerHTML = "";
            dropdown.style.display = 'block';

            if (products.length > 0) {
                TemplateEngine.renderList("dps_result_item", products, dropdown);
            } else {
                dropdown.innerHTML = '<div style="padding: 10px; text-align: center; color: #6b7280; font-size: 0.9rem;">No se encontraron resultados</div>';
            }
        });

        req.send();
    }

    function selectItem(event, ctrlInstance) {
        var itemEl = event.target.closest('.dps-item');
        var container = itemEl.closest('.dynamic-product-search');
        
        var id = itemEl.getAttribute('data-id');
        var name = itemEl.getAttribute('data-name');
        var sku = itemEl.getAttribute('data-sku');
        var price = itemEl.getAttribute('data-price'); // <-- Extract price attribute

        container.querySelector('[data-jsbind="hidden_id"]').value = id;
        container.querySelector('[data-jsbind="search_input"]').value = ''; 
        container.querySelector('[data-jsbind="dropdown"]').style.display = 'none';

        // Trigger callback passing product info including price
        if (typeof onProductSelectedCallback === 'function') {
            onProductSelectedCallback({ 
                id: id, 
                name: name, 
                sku: sku, 
                price: price !== null ? parseFloat(price) : 0 // <-- Pass parsed price
            }, itemEl);
        }
    }

    function onSelect(callback) {
        onProductSelectedCallback = callback;
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dynamic-product-search')) {
            document.querySelectorAll('.dynamic-product-search [data-jsbind="dropdown"]').forEach(function(el) {
                el.style.display = 'none';
            });
        }
    });

    exports.handleInput = handleInput;
    exports.handleFocus = handleFocus;
    exports.handleFilterChange = handleFilterChange;
    exports.selectItem = selectItem;
    exports.onSelect = onSelect;
});