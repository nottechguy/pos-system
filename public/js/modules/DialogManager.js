defineModule("DialogManager", [], function (
    global,
    requireModule,
    requireDynamic,
    requireLazy,
    module,
    exports
) {
    'use strict';

    var activeModal = null;
    var lastFocusedElement = null;
    var keydownHandler = null;

    function getFocusableElements(container) {
        return Array.from(
            container.querySelectorAll(
                'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
            )
        );
    }

    function handleKeydown(e) {
        if (!activeModal) return;

        if (e.key === 'Escape' && activeModal.closeOnEscape !== false) {
            e.preventDefault();
            exports.close();
            return;
        }

        if (e.key === 'Tab') {
            var focusable = getFocusableElements(activeModal.element);
            if (focusable.length === 0) return;

            var first = focusable[0];
            var last = focusable[focusable.length - 1];

            if (e.shiftKey) {
                if (document.activeElement === first) {
                    e.preventDefault();
                    last.focus();
                }
            } else {
                if (document.activeElement === last) {
                    e.preventDefault();
                    first.focus();
                }
            }
        }
    }

    exports.open = function (modalElement, options) {
        options = options || {};
        if (activeModal) {
            exports.close();
        }

        lastFocusedElement = document.activeElement;
        activeModal = {
            element: modalElement,
            closeOnEscape: options.closeOnEscape !== false,
            closeCallback: options.onClose || null
        };

        modalElement.addEventListener('click', function (e) {
            if (e.target === modalElement) {
                exports.close();
            }
        });

        keydownHandler = handleKeydown;
        document.addEventListener('keydown', keydownHandler);

        window.requestAnimationFrame(function () {
            var focusable = getFocusableElements(modalElement);
            if (focusable.length > 0) {
                focusable[0].focus();
            } else {
                modalElement.focus();
            }
        });
    };

    exports.close = function () {
        if (!activeModal) return;

        if (keydownHandler) {
            document.removeEventListener('keydown', keydownHandler);
            keydownHandler = null;
        }

        if (activeModal.element && activeModal.element.parentNode) {
            activeModal.element.remove();
        }

        if (typeof activeModal.closeCallback === 'function') {
            activeModal.closeCallback();
        }

        if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
            lastFocusedElement.focus();
        }

        activeModal = null;
        lastFocusedElement = null;
    };

    module.exports = exports;
}, null);