defineModule("LayoutController", ["CSSCore", "$"], (function(global, requireModule, requireDynamic, requireLazy, module, exports, CSSCore, $) {
    'use strict';
    
    function toggleSidebar(event, ctrlInstance) {
        var sidebar = $.fromIDOrElement('app_sidebar');
        if (sidebar) {
            CSSCore.toggleClass(sidebar, 'open');
        }
    }

    exports.toggleSidebar = toggleSidebar;
}), null);

defineModule("Dashboard", [], (function(global, requireModule, requireDynamic, requireLazy, module, exports) {
    'use strict';

    function checkForSessionResponse() {
        var snackbarActionResponse = requireModule("$").fromIDOrElement('snackbar_action_response');
        if (snackbarActionResponse) {
            var snackbar = new(requireModule("Snackbar"))(snackbarActionResponse);
            snackbar.setTimeoutMs(8000);
            snackbar.open();
        }
    }

    checkForSessionResponse();

}), true);