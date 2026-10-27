defineModule("TerminalsUIController", ["$"], function(global, requireModule, requireDynamic, requireLazy, module, exports, $) {
    'use strict';

    function confirmDeletion(event) {
        if (!confirm("Are you sure you want to delete this POS Terminal? The physical device will lose connection to the system.")) {
            event.preventDefault();
        }
    }

    exports.confirmDeletion = confirmDeletion;
});