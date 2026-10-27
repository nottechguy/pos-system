defineModule("CustomerPricesManager", ["DynamicProductSearch", "Parent"], function (global, requireModule, requireDynamic, requireLazy, module, exports, DPS, Parent) {
    'use strict';

    function init() {
        DPS.onSelect(function(product, referenceElement) {
            if (product && product.id) {
                var basePrice = product.price !== undefined ? product.price : 
                               (product.sale_price !== undefined ? product.sale_price : 
                               (product.base_price || 0));

                addProductRow(product.id, product.name, product.sku, basePrice, referenceElement, '');
            }
        });
    }

    function addProductRow(id, name, sku, basePrice, referenceElement, customPrice = '') {
        var tbody = document.querySelector('#selected-products-table tbody');
        var noProductsRow = document.getElementById('no-products-row');

        if (document.getElementById('prod_row_' + id)) {
            return; // Prevent duplicates
        }

        if (noProductsRow) {
            noProductsRow.remove();
        }

        var formattedBasePrice = Number(basePrice || 0).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        var tr = document.createElement('tr');
        tr.id = 'prod_row_' + id;
        tr.innerHTML = `
            <td>
                <strong>${name}</strong>
                <input type="hidden" name="products[${id}][product_id]" value="${id}">
            </td>
            <td>${sku || 'N/A'}</td>
            <td>$${formattedBasePrice}</td>
            <td>
                <input type="number" step="0.01" min="0" class="form-control" name="products[${id}][price]" value="${customPrice !== null && customPrice !== undefined ? customPrice : ''}" placeholder="Precio Especial" style="width: 130px; padding: 4px;" required data-id="${id}">
            </td>
            <td>
                <button type="button" class="btn btn-danger" style="background: none; border: none; color: red; cursor: pointer; font-weight: bold; padding: 4px 8px;" onclick="requireModule('CustomerPricesManager').removeProduct('${id}')">Eliminar</button>
            </td>
        `;
        tbody.appendChild(tr);
    }

    function removeProduct(id) {
        var row = document.getElementById('prod_row_' + id);
        if (row) row.remove();

        var tbody = document.querySelector('#selected-products-table tbody');
        if (tbody && tbody.children.length === 0) {
            tbody.innerHTML = `
                <tr id="no-products-row">
                    <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 15px;">No se han seleccionado productos.</td>
                </tr>
            `;
        }
    }

    function restoreProduct(id, name, sku, basePrice, customPrice) {
        addProductRow(id, name, sku, basePrice, null, customPrice);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    exports.removeProduct = removeProduct;
    exports.restoreProduct = restoreProduct;
    exports.init = init;
});