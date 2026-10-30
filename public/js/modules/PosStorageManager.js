defineModule("PosStorageManager", [], function (
    global,
    requireModule,
    requireDynamic,
    requireLazy,
    module,
    exports
) {
    'use strict';

    var STORAGE_KEY_CART = 'pos_active_cart';
    var STORAGE_KEY_CUSTOMER = 'pos_active_customer';
    var STORAGE_KEY_MODE = 'pos_active_print_mode';

    exports.saveCart = function (cart) {
        try {
            localStorage.setItem(STORAGE_KEY_CART, JSON.stringify(cart));
        } catch (e) {
            console.error("Failed to save cart to localStorage", e);
        }
    };

    exports.getCart = function () {
        try {
            var data = localStorage.getItem(STORAGE_KEY_CART);
            return data ? JSON.parse(data) : [];
        } catch (e) {
            console.error("Failed to retrieve cart from localStorage", e);
            return [];
        }
    };

    exports.saveCustomer = function (customer) {
        try {
            if (customer) {
                localStorage.setItem(STORAGE_KEY_CUSTOMER, JSON.stringify(customer));
            } else {
                localStorage.removeItem(STORAGE_KEY_CUSTOMER);
            }
        } catch (e) {
            console.error("Failed to save active customer to localStorage", e);
        }
    };

    exports.getCustomer = function () {
        try {
            var data = localStorage.getItem(STORAGE_KEY_CUSTOMER);
            return data ? JSON.parse(data) : null;
        } catch (e) {
            console.error("Failed to retrieve customer from localStorage", e);
            return null;
        }
    };

    exports.savePrintMode = function (mode) {
        try {
            localStorage.setItem(STORAGE_KEY_MODE, mode);
        } catch (e) {
            console.error("Failed to save print mode to localStorage", e);
        }
    };

    exports.getPrintMode = function () {
        try {
            return localStorage.getItem(STORAGE_KEY_MODE) || "Ticket";
        } catch (e) {
            return "Ticket";
        }
    };

    exports.clearSession = function () {
        try {
            localStorage.removeItem(STORAGE_KEY_CART);
            localStorage.removeItem(STORAGE_KEY_CUSTOMER);
            localStorage.removeItem(STORAGE_KEY_MODE);
        } catch (e) {
            console.error("Failed to clear POS session from localStorage", e);
        }
    };

    module.exports = exports;
}, null);