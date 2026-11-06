defineModule("CashShiftUIController", ["$", "AsyncRequest", "TemplateEngine", "DialogManager"], function (
    global, requireModule, requireDynamic, requireLazy, module, exports, $, AsyncRequest, TemplateEngine, DialogManager
) {
    'use strict';

    var csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';
    var currentActiveShiftId = null;

    // Register templates
    TemplateEngine.register("close_shift_modal", `
        <div class="modal-backdrop" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); display: flex; justify-content: center; align-items: center; z-index: 1200;" tabindex="-1">
            <div class="modal-content" style="background: #fff; padding: 2rem; border-radius: 8px; width: 650px; max-width: 95vw; max-height: 90vh; overflow-y: auto; box-shadow: 0 8px 24px rgba(0,0,0,0.2);">
                <h3 style="margin-top: 0;">Arqueo y Cierre de Turno</h3>
                <p style="color: #4b5563; font-size: 0.9rem;">Cuente el efectivo físico en caja ingresando la cantidad por cada denominación.</p>
                
                <form id="close_shift_form" data-jscontroller="CashShiftUIController" data-jssubmit="@submitCloseShift">
                    <div style="margin: 1rem 0; border: 1px solid #e5e7eb; border-radius: 6px; max-height: 250px; overflow-y: auto;">
                        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
                            <thead>
                                <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                                    <th style="padding: 10px;">Denominación</th>
                                    <th style="padding: 10px;">Cantidad</th>
                                    <th style="padding: 10px; text-align: right;">Total Parcial</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{#denominations}}
                                <tr>
                                    <td style="padding: 8px 10px; border-bottom: 1px solid #f3f4f6;">$\{{value}} ({{type}})</td>
                                    <td style="padding: 8px 10px; border-bottom: 1px solid #f3f4f6;">
                                        <input type="number" class="form-control denomination-qty" data-id="{{id}}" data-value="{{value}}" value="0" min="0" step="1" data-jscontroller="CashShiftUIController" data-jsinput="@calculateCountedTotal" style="width: 100px; padding: 4px;">
                                    </td>
                                    <td style="padding: 8px 10px; border-bottom: 1px solid #f3f4f6; text-align: right;" class="denomination-subtotal">$0.00</td>
                                </tr>
                                {{/denominations}}
                            </tbody>
                        </table>
                    </div>

                    <div style="display: flex; justify-content: space-between; font-size: 1.1rem; font-weight: bold; margin: 1rem 0; background: #f3f4f6; padding: 10px; border-radius: 6px;">
                        <span>Total Contado en Caja:</span>
                        <span id="counted_cash_display" style="color: #059669;">$0.00</span>
                    </div>

                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label for="close_notes" style="display: block; font-weight: 500; margin-bottom: 0.25rem;">Notas de Cierre:</label>
                        <textarea id="close_notes" class="form-control" rows="2" style="width: 100%; padding: 0.5rem;" placeholder="Observaciones o diferencias..."></textarea>
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                        <button type="button" class="btn btn-secondary" data-jscontroller="CashShiftUIController" data-jsclick="@closeModal" style="padding: 0.5rem 1rem; cursor: pointer;">Cancelar</button>
                        <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.5rem; background: #dc2626; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Confirmar Cierre de Turno</button>
                    </div>
                </form>
            </div>
        </div>
    `);

    TemplateEngine.register("shift_summary_modal", `
        <div class="modal-backdrop" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); display: flex; justify-content: center; align-items: center; z-index: 1200;" tabindex="-1">
            <div class="modal-content" style="background: #fff; padding: 2rem; border-radius: 8px; width: 750px; max-width: 95vw; max-height: 90vh; overflow-y: auto; box-shadow: 0 8px 24px rgba(0,0,0,0.2);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h3 style="margin: 0;">Reporte del Turno Actual (#{{shift.id}})</h3>
                    <span style="background: {{status_color}}; color: #fff; padding: 2px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: bold;">{{shift.status}}</span>
                </div>

                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
                    <div style="background: #f3f4f6; padding: 1rem; border-radius: 6px;">
                        <div style="font-size: 0.85rem; color: #4b5563;">Ventas Totales</div>
                        <div style="font-size: 1.25rem; font-weight: bold; color: #059669;">$\{{total_sales}}</div>
                    </div>
                    <div style="background: #f3f4f6; padding: 1rem; border-radius: 6px;">
                        <div style="font-size: 0.85rem; color: #4b5563;">Transacciones</div>
                        <div style="font-size: 1.25rem; font-weight: bold;">{{transaction_count}}</div>
                    </div>
                    <div style="background: #f3f4f6; padding: 1rem; border-radius: 6px;">
                        <div style="font-size: 0.85rem; color: #4b5563;">Diferencia Caja</div>
                        <div style="font-size: 1.25rem; font-weight: bold; color: {{diff_color}};">$\{{shift.difference}}</div>
                    </div>
                </div>

                <h4 style="margin-bottom: 0.5rem;">Productos Más Vendidos</h4>
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; font-size: 0.9rem;">
                    <thead>
                        <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                            <th style="padding: 8px;">Producto</th>
                            <th style="padding: 8px; text-align: center;">Cantidad</th>
                            <th style="padding: 8px; text-align: right;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{#top_products}}
                        <tr>
                            <td style="padding: 6px 8px; border-bottom: 1px solid #f3f4f6;">{{name}}</td>
                            <td style="padding: 6px 8px; border-bottom: 1px solid #f3f4f6; text-align: center;">{{total_qty}}</td>
                            <td style="padding: 6px 8px; border-bottom: 1px solid #f3f4f6; text-align: right;">$\{{total_revenue}}</td>
                        </tr>
                        {{/top_products}}
                    </tbody>
                </table>

                <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                    {{#is_closed}}
                    <button type="button" class="btn btn-warning" data-jscontroller="CashShiftUIController" data-jsclick="@reopenShift" data-shift-id="{{shift.id}}" style="padding: 0.5rem 1rem; background: #d97706; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Reabrir Turno</button>
                    {{/is_closed}}
                    <button type="button" class="btn btn-secondary" data-jscontroller="CashShiftUIController" data-jsclick="@closeModal" style="padding: 0.5rem 1rem; cursor: pointer;">Cerrar Ventana</button>
                </div>
            </div>
        </div>
    `);

    exports.openCloseShiftModal = function () {
        var req = new AsyncRequest("/admin/shifts/active/summary").setMethod("GET");
        req.onSuccess(function(res) {
            var data = JSON.parse(res.responseText);
            var activeShift = data.shift;

            if (!activeShift || activeShift.status !== 'open') {
                alert("No hay ningún turno abierto actualmente.");
                window.location.href = "/admin/shifts/create"; // Redirect to open shift if none active
                return;
            }

            currentActiveShiftId = activeShift.id; // Capture dynamic ID

            var modalEl = TemplateEngine.renderToElement("close_shift_modal", {
                denominations: data.denominations
            });
            modalEl.id = "active_shift_modal";
            document.body.appendChild(modalEl);
            DialogManager.open(modalEl);
        });
        req.onError(function() {
            alert("No se pudo verificar el estado del turno actual.");
        });
        req.send();
    };

    exports.openShiftSummaryModal = function () {
        var req = new AsyncRequest("/admin/shifts/active/summary").setMethod("GET");
        req.onSuccess(function(res) {
            var data = JSON.parse(res.responseText);
            var shift = data.shift;

            if (!shift) {
                alert("No hay información de turno disponible.");
                window.location.href = "/admin/shifts/create";
                return;
            }

            currentActiveShiftId = shift.id; // Capture dynamic ID

            data.status_color = shift.status === 'open' ? '#059669' : '#dc2626';
            data.diff_color = (shift.difference || 0) < 0 ? '#dc2626' : '#059669';
            data.is_closed = shift.status === 'closed';

            var modalEl = TemplateEngine.renderToElement("shift_summary_modal", data);
            modalEl.id = "active_shift_modal";
            document.body.appendChild(modalEl);
            DialogManager.open(modalEl);
        });
        req.send();
    };

    exports.calculateCountedTotal = function (event) {
        var inputs = document.querySelectorAll('.denomination-qty');
        var grandTotal = 0;

        inputs.forEach(function(input) {
            var qty = parseFloat(input.value) || 0;
            var val = parseFloat(input.getAttribute('data-value')) || 0;
            var subtotal = qty * val;
            grandTotal += subtotal;

            var subtotalEl = input.closest('tr').querySelector('.denomination-subtotal');
            if (subtotalEl) {
                subtotalEl.innerText = '$' + subtotal.toFixed(2);
            }
        });

        var totalDisplay = document.getElementById('counted_cash_display');
        if (totalDisplay) {
            totalDisplay.innerText = '$' + grandTotal.toFixed(2);
        }
    };

    exports.submitCloseShift = function (event, ctrlInstance) {
        event.preventDefault();

        if (!currentActiveShiftId) {
            alert("Error: No se ha identificado un turno activo.");
            return;
        }

        var countLines = [];
        document.querySelectorAll('.denomination-qty').forEach(function(input) {
            countLines.push({
                denomination_id: input.getAttribute('data-id'),
                quantity: parseFloat(input.value) || 0
            });
        });

        var notes = document.getElementById('close_notes').value;

        // Use dynamic shift ID instead of hardcoded 1
        var req = new AsyncRequest("/admin/shifts/" + currentActiveShiftId + "/close")
            .setMethod("POST")
            .setRequestHeaders({ "X-CSRF-TOKEN": csrfToken, "Content-Type": "application/json" })
            .setData(JSON.stringify({
                count_lines: countLines,
                notes: notes
            }));

        req.onSuccess(function() {
            alert("Turno cerrado exitosamente.");
            DialogManager.close();
            // Automatically redirect cashier to the open-shift screen
            window.location.href = "/admin/shifts/create";
        });

        req.onError(function(xhr) {
            try {
                var err = JSON.parse(xhr.responseText);
                alert("Error al cerrar turno: " + (err.message || "Desconocido"));
            } catch (e) {
                alert("Error en el servidor al cerrar turno.");
            }
        });

        req.send();
    };

    exports.reopenShift = function (event, ctrlInstance) {
        var shiftId = ctrlInstance.targetElement.getAttribute('data-shift-id');

        var req = new AsyncRequest("/admin/shifts/" + shiftId + "/reopen")
            .setMethod("POST")
            .setRequestHeaders({ "X-CSRF-TOKEN": csrfToken, "Content-Type": "application/json" });

        req.onSuccess(function() {
            alert("Turno reabierto con éxito.");
            DialogManager.close();
            window.location.reload();
        });

        req.send();
    };

    exports.closeModal = function () {
        DialogManager.close();
    };

    module.exports = exports;
}, null);