defineModule("PricingUIController", ["$"], function(global, requireModule, requireDynamic, requireLazy, module, exports, $) {
    'use strict';

    function confirmRuleDeletion(event) {
        if (!confirm("Remove this pricing rule? This will affect POS calculations immediately.")) {
            event.preventDefault();
        }
    }

    exports.confirmRuleDeletion = confirmRuleDeletion;
});