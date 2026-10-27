defineModule("StoresUIController", ["$"], function(global, requireModule, requireDynamic, requireLazy, module, exports, $) {
    'use strict';

    function confirmDeletion(event, ctrl) {
        console.log(event, ctrl);
        if (!confirm("Are you sure you want to delete this store? This action cannot be undone.")) {
            event.preventDefault();
        }
    }

    exports.confirmDeletion = confirmDeletion;
});