// Login Form Controller
defineModule("AuthLoginController", ["$"], function(global, requireModule, requireDynamic, requireLazy, module, exports, $) {
    'use strict';
    
    function AuthLoginController(event, formCtx) {
        this.formCtx = formCtx;
        this.submitBtn = this.formCtx.form.querySelector('button[type="submit"]');
    }

    AuthLoginController.prototype.onBeforeSubmit = function(payload) {
        this.submitBtn.disabled = true;
        this.submitBtn.textContent = 'Autenticando...';
        // Clear previous errors
        var errorNodes = this.formCtx.form.querySelectorAll('.error-text');
        for (var i = 0; i < errorNodes.length; i++) {
            errorNodes[i].style.display = 'none';
        }
    };

    AuthLoginController.prototype.onSuccess = function(payload, xhr) {
        var response = JSON.parse(xhr.responseText);
        window.location.href = response.redirect_url;
    };

    AuthLoginController.prototype.onError = function(xhr) {
        this.submitBtn.disabled = false;
        this.submitBtn.textContent = 'Iniciar sesión';
        
        if (xhr.status === 422) {
            var response = JSON.parse(xhr.responseText);
            if (response.errors) {
                for (var key in response.errors) {
                    var errorEl = $.fromIDOrElement('error_' + key);
                    if (errorEl) {
                        errorEl.textContent = response.errors[key][0];
                        errorEl.style.display = 'block';
                    }
                }
            }
        } else {
            alert('An unexpected error occurred.');
        }
    };

    module.exports = AuthLoginController;
});

// Business Setup Controller
defineModule("BusinessSetupController", ["$"], function(global, requireModule, requireDynamic, requireLazy, module, exports, $) {
    'use strict';
    
    function BusinessSetupController(event, formCtx) {
        this.formCtx = formCtx;
        this.submitBtn = this.formCtx.form.querySelector('button[type="submit"]');
    }

    BusinessSetupController.prototype.onBeforeSubmit = function(payload) {
        this.submitBtn.disabled = true;
        this.submitBtn.textContent = 'Saving...';
    };

    BusinessSetupController.prototype.onSuccess = function(payload, xhr) {
        var response = JSON.parse(xhr.responseText);
        window.location.href = response.redirect_url;
    };

    BusinessSetupController.prototype.onError = function(xhr) {
        this.submitBtn.disabled = false;
        this.submitBtn.textContent = 'Complete Setup';
        
        if (xhr.status === 422) {
            var response = JSON.parse(xhr.responseText);
            // Handle specific field errors similar to login
            alert("Please check the form for errors."); 
        }
    };

    module.exports = BusinessSetupController;
});