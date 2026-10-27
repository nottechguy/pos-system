defineModule("BusinessUIController", ["$"], function(global, requireModule, requireDynamic, requireLazy, module, exports, $) {
    'use strict';

    function showBannerImagePreview(event, ctrlInstance) {
        var imagePreview = requireModule("$").fromIDOrElement(ctrlInstance.getParam("image_preview"));
        var input = event.target;

        var imagePreviewContainer = requireModule("$").fromIDOrElement("business_dialog_image_preview_container");
        var imageFileContainer = requireModule("$").fromIDOrElement("business_dialog_image_file_container");

        if (input.files && input.files[0]) {
            var file = input.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                imagePreviewContainer.style.visibility = "visible";
                imagePreviewContainer.style.display = "block";
                imageFileContainer.style.display = "none";
                imagePreview.style.opacity = 1;
                imagePreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            imagePreview.style.opacity = 0;
        }
    }

    function clearBannerImagePreview(event, ctrlInstance) {
        var imagePreview = requireModule("$").fromIDOrElement("logo_preview_img");
        var input = requireModule("$").fromIDOrElement("business_image_file_input");

        var imagePreviewContainer = requireModule("$").fromIDOrElement("business_dialog_image_preview_container");
        var imageFileContainer = requireModule("$").fromIDOrElement("business_dialog_image_file_container");

        imagePreview.src = "#";
        imagePreview.style.opacity = 0;
        input.value = "";

        imagePreviewContainer.style.visibility = "hidden";
        imagePreviewContainer.style.display = "none";
        imageFileContainer.style.display = "flex";
    }

    exports.showBannerImagePreview = showBannerImagePreview;
    exports.clearBannerImagePreview = clearBannerImagePreview;
});