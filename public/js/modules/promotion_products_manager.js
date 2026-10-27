defineModule("PromotionProductsManager", ["DynamicProductSearch", "Parent"], function (global, requireModule, requireDynamic, requireLazy, module, exports, DPS, Parent) {
    'use strict';

    function init() {
        DPS.onSelect(function(product, referenceElement) {
            if (product && product.id) {
                var basePrice = product.price !== undefined ? product.price : 
                               (product.sale_price !== undefined ? product.sale_price : 
                               (product.base_price || 0));

                addProductRow(product.id, product.name, product.sku, basePrice, referenceElement, '', '');
            }
        });
    }

    function addProductRow(id, name, sku, basePrice, referenceElement, promoPrice = '', discountVal = '') {
        var form = referenceElement ? Parent.byTag(referenceElement, 'FORM') : document.querySelector('form');
        if (!form) form = document.querySelector('form');

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
                <input type="number" step="0.01" min="0" class="form-control" name="products[${id}][promotional_price]" value="${promoPrice !== null && promoPrice !== undefined ? promoPrice : ''}" placeholder="Precio Promo" style="width: 110px; padding: 4px;" data-id="${id}">
            </td>
            <td>
                <input type="number" step="0.01" min="0" class="form-control" name="products[${id}][discount_value]" value="${discountVal !== null && discountVal !== undefined ? discountVal : ''}" placeholder="Descuento" style="width: 110px; padding: 4px;" data-id="${id}">
            </td>
            <td>
                <button type="button" class="btn btn-danger" style="background: none; border: none; color: red; cursor: pointer; font-weight: bold; padding: 4px 8px;" onclick="requireModule('PromotionProductsManager').removeProduct('${id}')">Eliminar</button>
            </td>
        `;
        tbody.appendChild(tr);

        if (typeof window.updatePromotionUI === 'function') {
            window.updatePromotionUI();
        }
    }

    function removeProduct(id) {
        var row = document.getElementById('prod_row_' + id);
        if (row) row.remove();

        var tbody = document.querySelector('#selected-products-table tbody');
        if (tbody && tbody.children.length === 0) {
            tbody.innerHTML = `
                <tr id="no-products-row">
                    <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 15px;">No se han seleccionado productos.</td>
                </tr>
            `;
        }

        if (typeof window.updatePromotionUI === 'function') {
            window.updatePromotionUI();
        }
    }

    function restoreProduct(id, name, sku, basePrice, promoPrice, discountVal) {
        addProductRow(id, name, sku, basePrice, null, promoPrice, discountVal);
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