// 1. Core Global Scanner Engine
defineModule("GlobalBarcodeScanner", ["$"], function(global, requireModule, requireDynamic, requireLazy, module, exports, $) {
    'use strict';
    var buffer = "";
    var lastTime = 0;
    var callbacks = [];
    var THRESHOLD = 50; // Max ms between keystrokes (Scanners usually fire every 5-15ms)

    // We attach to the 'capture' phase (true) to intercept keys BEFORE the browser processes them
    document.addEventListener('keydown', function(e) {
        // Ignore standalone modifier keys entirely
        if (['Shift', 'Control', 'Alt', 'Meta', 'AltGraph'].indexOf(e.key) !== -1) {
            return;
        }

        var now = Date.now();
        var timeDiff = now - lastTime;
        var isScanning = timeDiff <= THRESHOLD;

        // If the delay is too long (human typing), reset the buffer
        if (!isScanning) {
            buffer = "";
        }

        lastTime = now;

        // 1. Handle Terminator Key (Standard scanners end with Enter)
        if (e.key === 'Enter') {
            if (buffer.length >= 3) {
                // It's a completed scan sequence! Stop the Enter from submitting forms natively.
                e.preventDefault(); 
                e.stopPropagation();
                
                var finalBarcode = buffer;
                buffer = ""; // Reset for next scan
                
                // Fire all registered listeners
                callbacks.forEach(function(cb) { cb(finalBarcode); });
            }
            return;
        }

        // 2. Handle valid characters
        if (e.key.length === 1) {
            buffer += e.key;
            
            // If we are deep into a rapid scan sequence, block the native event.
            // This prevents the scanner from dumping characters into random focused text inputs.
            if (isScanning && buffer.length > 1) {
                e.preventDefault();
                e.stopPropagation();
            }
        }
    }, true);

    exports.onScan = function(callback) {
        callbacks.push(callback);
    };
});

// 2. Form Scanner Controller (For Create / Edit Views)
defineModule("ProductFormScanner", ["$", "GlobalBarcodeScanner"], function(global, requireModule, requireDynamic, requireLazy, module, exports, $, Scanner) {
    'use strict';
    
    function init(targetInputId) {
        Scanner.onScan(function(barcode) {
            var input = $.fromIDOrElement(targetInputId);
            if (input) {
                input.value = barcode;
                // Visual flash to confirm scan
                input.style.backgroundColor = '#e8f5e9';
                setTimeout(function() { input.style.backgroundColor = ''; }, 300);
            }
        });
    }
    
    exports.init = init;
});

// 3. Async Search Controller (For Index View)
defineModule("ProductAsyncSearch", ["$", "GlobalBarcodeScanner", "AsyncRequest"], function(global, requireModule, requireDynamic, requireLazy, module, exports, $, Scanner, AsyncRequest) {
    'use strict';

    function init(searchUrl, csrfToken) {
        Scanner.onScan(function(barcode) {
            var searchInput = $.fromIDOrElement('search_input');
            if (searchInput) searchInput.value = barcode;

            // Send AsyncRequest immediately upon scan
            new AsyncRequest(searchUrl)
                .setMethod('POST')
                .setData({ barcode: barcode, _token: csrfToken })
                .onSuccess(function(response) {
                    var data = JSON.parse(response.responseText);
                    if (data.found && data.redirect_url) {
                        // Redirect directly to the product edit page
                        window.location.href = data.redirect_url;
                    }
                })
                .onError(function(xhr) {
                    if (searchInput) searchInput.value = '';
                    alert("Barcode not found in database: " + barcode);
                })
                .send();
        });
    }

    exports.init = init;
});