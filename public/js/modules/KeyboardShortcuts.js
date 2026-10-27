defineModule("KeyboardShortcuts", [], function (global, requireModule, requireDynamic, requireLazy, module, exports) {
    'use strict';

    var shortcuts = {};
    var isInitialized = false;

    function handleKeydown(event) {
        // Normalize key to lowercase (e.g., 'f5', 'enter', 'escape')
        var key = event.key.toLowerCase();

        if (shortcuts.hasOwnProperty(key)) {
            // Prevent default browser behavior (like refreshing on F5)
            event.preventDefault();
            
            // Execute the registered callback
            shortcuts[key](event);
        }
    }

    function register(key, callback) {
        shortcuts[key.toLowerCase()] = callback;
        console.log(shortcuts);
    }

    function unregister(key) {
        delete shortcuts[key.toLowerCase()];
    }

    function init() {
        if (isInitialized) return;
        document.addEventListener('keydown', handleKeydown, true); // Use capture phase
        isInitialized = true;
    }

    exports.register = register;
    exports.unregister = unregister;
    exports.init = init;

}, null);