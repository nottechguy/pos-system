defineModule("ProductsUIController", ["$"], function(global, requireModule, requireDynamic, requireLazy, module, exports, $) {
    'use strict';

    function confirmDeletion(event) {
        if (!confirm("Are you sure you want to delete this product?")) {
            event.preventDefault();
        }
    }

    // Prevents barcode scanners (which send 'Enter' at the end) from submitting the whole form.
    // Attach this to the barcode input field.
    function handleBarcodeScan(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            // Move focus to the next logical field (e.g., Name)
            var nextField = $.fromIDOrElement('name');
            if (nextField) nextField.focus();
        }
    }

    // Generates a valid EAN-13 barcode for in-store items (Prefix 20)
    function generateInternalBarcode(event) {
        event.preventDefault();
        
        // Prefix '20' is standard for internal store use
        var prefix = '20';
        // Generate 10 random digits
        var randomDigits = Math.floor(Math.random() * 9000000000) + 1000000000;
        var barcodeBase = prefix + randomDigits.toString();
        
        // Calculate EAN-13 Checksum
        var sum = 0;
        for (var i = 0; i < 12; i++) {
            sum += parseInt(barcodeBase.charAt(i)) * (i % 2 === 0 ? 1 : 3);
        }
        var checksum = (10 - (sum % 10)) % 10;
        
        var finalBarcode = barcodeBase + checksum.toString();
        
        var barcodeInput = $.fromIDOrElement('primary_barcode');
        if (barcodeInput) {
            barcodeInput.value = finalBarcode;
            // Flash effect for visual feedback
            barcodeInput.style.backgroundColor = '#e8f5e9';
            setTimeout(function() { barcodeInput.style.backgroundColor = ''; }, 300);
        }
    }

    exports.confirmDeletion = confirmDeletion;
    exports.handleBarcodeScan = handleBarcodeScan;
    exports.generateInternalBarcode = generateInternalBarcode;
});