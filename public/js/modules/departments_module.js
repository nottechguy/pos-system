defineModule("DepartmentsUIController", ["$"], function(global, requireModule, requireDynamic, requireLazy, module, exports, $) {
    'use strict';

    function confirmDeletion(event) {
        if (!confirm("Are you sure you want to delete this department? Any associated product categories may be affected.")) {
            event.preventDefault();
        }
    }

    exports.confirmDeletion = confirmDeletion;
});