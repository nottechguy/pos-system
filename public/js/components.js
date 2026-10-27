
defineModule("components:classlist", [], (function(global, requireModule, requireDynamic, requireLazy, module, exports) {
    'use strict';
    var classList = {
        'badge': {
            'badge': 'c_badge',
            'small': 'c_badge--small',
            'single_digit': 'c_badge--single-digit',
            'large': 'c_badge--large'
        },
        'ripple': {
            'ripple': 'c_ripple',
            'fill': 'c_ripple--fill',
            'child': 'c_ripple__ripple',
            'child_done': 'c_ripple__ripple--done',
            'child_held': 'c_ripple__ripple--held',
        },
        'icon_button': {
            'container':  'c_icon-button',
            'on':  'c_icon-button--on',
            'icon':  'c_icon-button__icon',
            'icon_on':  'c_icon-button__icon--on',
            'ripple':  'c_icon-button__ripple'
        },
        'button': {
            'button': 'c_button',
            'raised': 'c_button--raised',
            'outlined': 'c_button--outlined',
            'unelevated': 'c_button--unelevated',
            'shaped': 'c_button--shaped',
            'disabled': 'c_button--disabled',
            'label': 'c_button__label',
            'icon': 'c_button__icon',
            'loading_icon': 'c_button__icon--loading',
            'ripple': 'c_button__ripple'
        },
        'checkbox' : {
            'container': 'c_checkbox',
            'focused' : 'c_checkbox--focused',
            'disabled': 'c_checkbox--disabled',
            'input': 'c_checkbox__native-control',
            'background': 'c_checkbox__background',
            'checkmark': 'c_checkbox__checkmark',
            'checkmark_path': 'c_checkbox__checkmark-path',
            'mixedmark': 'c_checkbox__mixed-mark'
        },
        'chip': {
            'chipset': 'c_chip-set',
            'outlined': 'c_chip-set--outlined',
            'chips': 'c_chip-set__chips',
            'input': 'c_chip-set--input',
            'filter': 'c_chip-set--filter',
            'choice': 'c_chip-set--choice',
            'action': 'c_chip-set--action',
            'shaped': 'c_chip--shaped',
            'chip': 'c_chip',
            'chip_error': 'c_chip--error',
            'chip_selected': 'c_chip--selected',
            'chip_expandable': 'c_chip--expandable',
            'chip_icon': 'c_chip__icon',
            'chip_icon_leading': 'c_chip__icon--leading',
            'chip_icon_leading_hidden': 'c_chip__icon--leading-hidden',
            'chip_icon_trailing': 'c_chip__icon--trailing',
            'chip_checkmark': 'c_chip__checkmark',
            'chip_checkmark_svg': 'c_chip__checkmark-svg',
            'chip_text': 'c_chip__text'
        },
        'dialog': {
            'dialog':  'c_dialog',
            'container': 'c_dialog__container',
            'alert': 'c_dialog--alert',
            'simple': 'c_dialog--simple',
            'confirmation': 'c_dialog--confirmation',
            'fullscreen': 'c_dialog--fullscreen',
            'scrollable': 'c_dialog--scrollable',
            'open': 'c_dialog--open',
            'opening': 'c_dialog--opening',
            'closing': 'c_dialog--closing',
            'scrollable': 'c_dialog--scrollable',
            'stacked': 'c_dialog--stacked',
            'scrim': 'c_dialog__scrim',
            'scrim_removed': 'c_dialog__scrim--removed',
            'container': 'c_dialog__container',
            'surface': 'c_dialog__surface',
            'header': 'c_dialog__header',
            'title': 'c_dialog__title',
            'content': 'c_dialog__content',
            'actions': 'c_dialog__actions',
            'button': 'c_dialog__button',
            'positive_button': 'c_dialog__button--positive',
            'negative_button': 'c_dialog__button--negative'
        },
        'list': {
            'container': 'c_list',
            'no_selection': 'c_list--no-selection',
            'item': 'c_list-item',
            'meta': 'c_list-item__meta',
            'selected': 'c_list-item--selected',
            'activated': 'c_list-item--activated',
            'disabled': 'c_list-item--disabled',
            'focused': 'c_list-item--focused',
            'non_interactive': 'c_list--non-interactive',
            'inner_list': 'c_list-item__inner-list',
            'inner_list_collapsed': 'c_list-item__inner-list--collapsed',
            'with_collapsed_list': 'c_list-item--with-collapsed-list'
        },
        'menu': {
            'container': 'c_menu',
            'exposed': 'c_menu--exposed',
            'end_aligned': 'c_menu--end-aligned',
            'list': 'c_list',
            'item': 'c_list-item',
            'item_activated': 'c_list-item--activated',
            'item_selected': 'c_list-item--selected',
            'surface': 'c_menu-surface',
            'open': 'c_menu-surface--open',
            'fixed': 'c_menu-surface--fixed',
            'opening': 'c_menu-surface--animating-open',
            'closing': 'c_menu-surface--animating-closed',
            'anchor': 'c_menu-surface--anchor'
        },
        'navigation_drawer': {
            'container':  'c_navigation-drawer',
            'permanent':  'c_navigation-drawer--permanent',
            'dismissible':  'c_navigation-drawer--dismissible',
            'modal':  'c_navigation-drawer--modal',
            'header':  'c_navigation-drawer__header',
            'title':  'c_navigation-drawer__title',
            'subtitle':  'c_navigation-drawer__subtitle',
            'content':  'c_navigation-drawer__content',
            'item': 'c_list-item',
        },
        'radio' : {
            'container': 'c_radio',
            'disabled': 'c_radio--disabled',
            'input': 'c_radio__native-control',
            'background': 'c_radio__background',
            'inner_circle': 'c_radio__inner-circle',
            'outer_circle': 'c_radio__outer-circle',
        },
        'select': {
            'container'         : 'c_select',
            'anchor'            : 'c_select__anchor',
            'outlined'          : 'c_select--outlined',
            'filled'            : 'c_select--filled',
            'focused'           : 'c_select--focused',
            'disabled'          : 'c_select--disabled',
            'invalid'           : 'c_select--invalid',
            'activated'         : 'c_select--activated',
            'with_leading_icon' : 'c_textfield--with-leading-icon',
            'no_label'          : 'c_select--no-label',
            'dense'             : 'c_select--dense',
            'readonly'          : 'c_select--readonly',
            'dropdown_icon'     : 'c_select__dropdown-icon',
            'selected_text'     : 'c_select__selected-text',
            'input'             : 'c_select__input',
            'label'             : 'c_select-floating-label',
            'label_floating'    : 'c_select-floating-label--floating',
            'label_float_above' : 'c_select-floating-label--float-above',
            'label_required'    : 'c_select-floating-label--required',
            'notched_outline'   : 'c_select-notched-outline',
            'notched_outline_notched' : 'c_select-notched-outline--notched',
            'notched_leading'    : 'c_select-notched-outline__leading',
            'notched_trailing'   : 'c_select-notched-outline__trailing',
            'notched_notch'      : 'c_select-notched-outline__notch',
            'line_ripple'                   : 'c_select__line-ripple',
            'line_ripple_active'            : 'c_select__line-ripple--active',
            'line_ripple_deactivating'      : 'c_select__line-ripple-deactivating',
            'helper_line'                   : 'c_select-helper-line',
            'helper_text'                   : 'c_select-helper-text',
            'helper_text_persistent'        : 'c_select-helper-text--persistent',
        },
        'snackbar': {
            'container'         : 'c_snackbar',
            'opening'           : 'c_snackbar--opening',
            'open'              : 'c_snackbar--open',
            'closing'           : 'c_snackbar--closing',
            'leading'           : 'c_snackbar--leading',
            'stacked'           : 'c_snackbar--stacked',
            'surface'           : 'c_snackbar__surface',
            'label'             : 'c_snackbar__label',
            'actions'           : 'c_snackbar__actions',
            'action'            : 'c_snackbar__action',
            'dismiss'           : 'c_snackbar__dismiss'
        },
        'switch': {
            'container': 'c_switch',
            'focused': 'c_switch--focused',
            'checked': 'c_switch--checked',
            'disabled': 'c_switch--disabled',
            'input': 'c_switch__native-control',
            'track': 'c_switch__track',
            'thumb': 'c_switch__thumb',
            'thumb_underlay': 'c_switch__thumb-underlay',
            'ripple': 'c_switch__ripple',
        },
        'tab' : {
            'tabbar'                : 'c_tab-bar',
            'primary'               : 'c_tab-bar--primary',
            'secondary'             : 'c_tab-bar--secondary',
            'scroller'              : 'c_tab-scroller',
            'scroller_area'         : 'c_tab-scroller__scroll-area',
            'scroller_content'      : 'c_tab-scroller__scroll-content',
            'container'             : 'c_tab',
            'active'                : 'c_tab--active',
            'focused'               : 'c_tab--focused',
            'content'               : 'c_tab__content',
            'label'                 : 'c_tab__text-label',
            'icon'                  : 'c_tab__icon',
            'badge'                 : 'c_tab__badge',
            'indicator'             : 'c_tab-indicator',
            'indicator_active'      : 'c_tab-indicator--active',
            'indicator_content'     : 'c_tab-indicator__content',
            'indicator_underline'   : 'c_tab-indicator__content--underline',
            'no_transition'         : 'c_tab-indicator--no-transition',
            'pane'                  : 'c_tab-pane'
        },
        'top_app_bar': {
            'container'                 : 'c_top-app-bar',
            'fixed'                     : 'c_top-app-bar--fixed',
            'prominent'                 : 'c_top-app-bar--prominent',
            'dense'                     : 'c_top-app-bar--dense',
            'short'                     : 'c_top-app-bar--short',
            'short_collapsed'           : 'c_top-app-bar--short-collapsed',
            'center_aligned'            : 'c_top-app-bar--center-aligned',
            'search'                    : 'c_top-app-bar--search',
            'search_collapsed'          : 'c_top-app-bar--search-collapsed',
            'search_active'             : 'c_top-app-bar--search-active',
            'search_expanded'           : 'c_top-app-bar--search-expanded',
            'search_full_width'         : 'c_top-app-bar--search-full-width',
            'row'                       : 'c_top-app-bar__row',
            'section'                   : 'c_top-app-bar__section',
            'section_align_start'       : 'c_top-app-bar__section--align-start',
            'section_align_end'         : 'c_top-app-bar__section--align-end',
            'navigation_icon'           : 'c_top-app-bar__navigation-icon',
            'title'                     : 'c_top-app-bar__title',
            'action_item'               : 'c_top-app-bar__action-item',
            'search_container'          : 'c_top-app-bar__search-container',
            'search_trigger'            : 'c_top-app-bar__search-trigger',
            'search_input'              : 'c_top-app-bar__search-input',
            'search_icon'               : 'c_top-app-bar__search-icon',
            'search_clear'              : 'c_top-app-bar__search-clear',
            'fixed_adjust'              : 'c_top-app-bar--fixed-adjust',
            'prominent_fixed_adjust'    : 'c_top-app-bar--prominent-fixed-adjust',
            'dense_fixed_adjust'        : 'c_top-app-bar--dense-fixed-adjust',
            'short_fixed_adjust'        : 'c_top-app-bar--short-fixed-adjust',
            'scrolled'                  : 'c_top-app-bar--scrolled'
        },
        'textfield': {
            'container'         : 'c_textfield',
            'outlined'          : 'c_textfield--outlined',
            'filled'            : 'c_textfield--filled',
            'focused'           : 'c_textfield--focused',
            'disabled'          : 'c_textfield--disabled',
            'error'             : 'c_textfield--invalid',
            'activated'         : 'c_textfield--activated',
            'label_floating'    : 'c_textfield--label-floating',
            'with_leading_icon' : 'c_textfield--with-leading-icon',
            'with_trailing_icon': 'c_textfield--with-trailing-icon',
            'with_action_button': 'c_textfield--with-action-button',
            'no_label'          : 'c_textfield--no-label',
            'readonly'          : 'c_textfield--readonly',
            'label'             : 'c_textfield-floating-label',
            'label_float_above' : 'c_textfield-floating-label--float-above',
            'label_required'    : 'c_textfield-floating-label--required',
            'label_shake'       : 'c_textfield-floating-label--shake',
            'icon'              : 'c_textfield__icon',
            'icon_disabled'     : 'c_textfield__icon--disabled',
            'icon_leading'      : 'c_textfield__icon--leading',
            'icon_trailing'     : 'c_textfield__icon--trailing',
            'supporting_text'   : 'c_textfield__supporting-text',
            'notched_outline'   : 'c_textfield-notched-outline',
            'notched_outline_notched' : 'c_textfield-notched-outline--notched',
            'notched_leading'    : 'c_textfield-notched-outline__leading',
            'notched_trailing'   : 'c_textfield-notched-outline__trailing',
            'notched_notch'      : 'c_textfield-notched-outline__notch',
            'helper_line'        : 'c_textfield-helper-line',
            'helper_text'        : 'c_textfield-helper-text',
            'helper_text_validation_msg'    : 'c_textfield-helper-text--validation-msg',
            'helper_text_persistent'        : 'c_textfield-helper-text--persistent',
            'character_counter'             : 'c_textfield__character-counter',
            'character_counter_active'      : 'c_textfield__character-counter--active',
            'character_counter_inactive'    : 'c_textfield__character-counter--inactive',
            'character_counter_error'       : 'c_textfield__character-counter--error',
            'input'                         : 'c_textfield__input',
            'input_file_label'              : 'c_textfield__input-file-label',
            'input_file_size'               : 'c_textfield__input-file-size',
            'line_ripple'                   : 'c_textfield__line-ripple',
            'line_ripple_active'            : 'c_textfield__line-ripple--active',
            'action_button'                 : 'c_icon-button'
        }
    };

    exports['default'] = classList;
}), 66);

defineModule("components:state", [], (function(global, requireModule, requireDynamic, requireLazy, module, exports) {
    'use strict';
    exports.FOCUSED_TAB = null;
    exports.LAST_FOCUSED_ELEMENT = null;
    exports.ACTIVE_DIALOG = null;
    exports.ACTIVE_MENU = null;
    exports.ACTIVE_MENU_TRIGGER = null;
}), 66);

defineModule("components:events", [], (function(global, requireModule, requireDynamic, requireLazy, module, exports) {

    function signal(target, eventType, handler, passive) {
        eventType = eventType.toLowerCase();
        requireModule("Event").addEventListener(target, eventType, handler, passive);
    }

    var events = {
        signal: signal
    };

    exports['default'] = events;
}), 66);

defineModule("components:utils", [], (function(global, requireModule, requireDynamic, requireLazy, module, exports) {

    function formatFileSize(bytes) {
        var decimals = 2;
        if (bytes === 0) return '0 Bytes';

        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        const i = Math.floor(Math.log(bytes) / Math.log(k));

        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }

    var events = {
        formatFileSize: formatFileSize
    };

    exports['default'] = events;
}), 66);

defineModule("components:form", [], (function(global, requireModule, requireDynamic, requireLazy, module, exports) {
    var selectClassList = requireModule("components:classlist").select;

    function resetComponentsState(form) {
        var elements = Array.from(form.elements);

        elements.forEach(function(element) {
            var tagName = element.tagName.toLowerCase();

            var type = element.type;
            var role = element.role;

            if (tagName === "input" || tagName === "textarea") {
                /*if (type == "checbox" && role !== "switch") {

                } else */
                if (type == "checkbox" && role == "switch") {
                    if (element.checked) {
                        element.checked = false;
                    } else {
                        element.checked = true;
                        const changeEvent = new Event('click', { bubbles: true });
                        element.dispatchEvent(changeEvent);
                    }
                } else if (type == "radio") {
                } else if (type == "hidden") {
                    if (requireModule("Parent").byClass(element, selectClassList.container)) {
                        requireModule("SelectAdapter").reset(element);
                    }
                } else if ((type !== "checkbox" && type !== "radio" && type !== "hidden") || tagName === "textarea") {
                    requireModule("TextfieldAdapter").reset(element);
                }

            } else if (tagName === "button") {
                
            }
        });

        form.reset();
    }

    function resetForm(content) {
        var forms = requireModule("Children").byTag(content, "form");
        if (forms.length) {
            forms.forEach(resetComponentsState);
            return;
        }

        // Reset all input components that are non part of a form
        var inputs = requireModule("Children").byTag(content, "input");
        if (inputs.length) {
            inputs.forEach(function(i) {
                if (i.type == "radio" || i.type == "checkbox") {
                    i.checked = false;
                }
                i.value = i.defaultValue;
            });
        }
    }

    var events = {
        reset: resetForm
    };

    exports['default'] = events;
}), 66);

defineModule("BadgeAdapter", ["CSSCore", "Parent"], (function(global, requireModule, requireDynamic, requireLazy, module, exports) {
    'use strict';
    
    var classList = requireModule("components:classlist").badge;
    
    function getInstance(count) {
        if (count == 0 || count < 0) return;
        count = count.toString();
        var badge = document.createElement("span");
        requireModule("CSSCore").addClass(badge, classList.badge);

        if (count == "1") {
            requireModule("CSSCore").addClass(badge, classList.small);
        } else if (count.length == 1 && count !== "1") {
            requireModule("CSSCore").addClass(badge, classList.single_digit);
        } else if (count.length > 2) {
            requireModule("CSSCore").addClass(badge, classList.large);
        }

        if (count !== "1") {
            badge.innerText = count;
        } else if (parseInt(count) > 99) {
            badge.innerText = "99+";
        }

        return badge;
    }

    module.exports = {
        getInstance: getInstance
    };
}), null);

defineModule("Badge", ["BadgeAdapter"], (function(global, requireModule, requireDynamic, requireLazy, module, exports, adapter) {
    'use strict';

    function badge(count) {
        this._root = adapter.getInstance(count);
    }

    module.exports = badge;
}), null);

defineModule("Icon", ["CSSCore", "Parent"], (function(global, requireModule, requireDynamic, requireLazy, module, exports) {
    'use strict';

    /**
     *
     * @param {HTMLElement} element
     * @param {string} icon
     */
    function setIcon(element, icon) {
        element.setAttribute("data-icon", icon);
        element.textContent = icon;
    }

    module.exports = {
        setIcon: setIcon
    };
}), null);

defineModule("ButtonAdapter", ["EventType", "CSSCore", "Parent"], (function(global, requireModule, requireDynamic, requireLazy, module, exports, eventTypes) {
    'use strict';

    var classList = requireModule("components:classlist").button;

    var loadingSVGIcon = `
        <svg class="button-spinner" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="currentColor">
            <path d="M480-96q-78.72 0-148.8-30.24-70.08-30.24-122.4-82.56-52.32-52.32-82.56-122.4Q96-401.28 96-480q0-79.68 30.22-149.28 30.21-69.6 82.49-121.92 52.28-52.32 122.3-82.56Q401.04-864 479.69-864 496-864 506-854t10 25.51q0 15.51-10 26T480-792q-129.67 0-220.84 90.5Q168-611 168-480.5T259.16-259q91.17 91 220.84 91 131 0 221.5-91.16Q792-350.33 792-480q0-16 10.49-26t26-10Q844-516 854-506q10 10 10 26.31 0 78.65-30.24 148.68-30.24 70.02-82.56 122.3-52.32 52.28-121.92 82.49Q559.68-96 480-96Z"/>
        </svg>
    `;
    var previousIcon = null;

    var eventHandler = {
        handleClickEvent: function(target, event) {
        },
    };

    var adapter = {
        handleClickEvent: eventHandler.handleClickEvent,
        addClass: function(className) {
            requireModule("CSSCore").addClass(this._root, className);
        },
        removeClass: function(className) {
            requireModule("CSSCore").removeClass(this._root, className);
        },
        setDisabled: function(disabled) {
            this._root.disabled = disabled;
        },
        setAttribute: function(attr, value) {
            this._root.setAttribute(attr, value);
        },
        removeAttribute: function(attr) {
            this._root.removeAttribute(attr, value);
        },
        setLoadingState: function(button, mustDisable) {
            var leadingIcon = requireModule("Child").byClass(button, classList.icon);
            console.log(leadingIcon, previousIcon);
            if (leadingIcon) {
                previousIcon = leadingIcon.innerHTML;
                leadingIcon.innerHTML = loadingSVGIcon;
            } else {
                leadingIcon = document.createElement("span");
                leadingIcon.innerHTML = loadingSVGIcon;
                requireModule("CSSCore").addClass(leadingIcon, classList.icon);
                requireModule("CSSCore").addClass(leadingIcon, classList.loading_icon);

                button.insertBefore(leadingIcon, button.firstChild);
            }
            if (typeof mustDisable == "boolean") button.disabled = mustDisable;
        },
        removeLoadingState: function(button, mustDisable) {
            var leadingIcon = requireModule("Child").byClass(button, classList.icon);
            console.log(leadingIcon, previousIcon);
            if (leadingIcon) {
                if (previousIcon !== null && requireModule("CSSCore").hasClass(leadingIcon, classList.loading_icon)) {
                    leadingIcon.innerHTML = previousIcon;
                } else {
                    button.removeChild(leadingIcon);
                }
            }
            if (typeof mustDisable == "boolean") button.disabled = mustDisable;
        },
        setText: function(button, text) {
            var label = requireModule("Child").byClass(button, classList.label);
            if (!label) return;
            label.textContent = text;
        }
    };

    module.exports = adapter;
}), null);

defineModule("Button", ["ButtonAdapter"], (function(global, requireModule, requireDynamic, requireLazy, module, exports, adapter) {
    'use strict';

    function button(id) {
        this._root = requireModule("$").fromIDOrElement(id);
    }
    button.prototype.addClass = function(className) {
        if (typeof className !== "string") throw new TypeError("argument 'className' must be a string");
        adapter.addClass.call(this, className);
    };
    button.prototype.removeClass = function(className) {
        if (typeof className !== "string") throw new TypeError("argument 'className' must be a string");
        adapter.removeClass.call(this, className);
    };
    button.prototype.hasClass = function(className) {
        if (typeof className !== "string") throw new TypeError("argument 'className' must be a string");
        adapter.removeClass.call(this, className);
    };
    button.prototype.setDisabled = function(disabled) {
        if (typeof disabled !== "boolean") throw new TypeError("argument 'disabled' must be a boolean");
        adapter.setDisabled.call(this, disabled);
    };
    button.prototype.setAttribute = function(attr, value) {
        if (typeof attr !== "string") throw new TypeError("argument 'attr' must be a string");
        if (typeof value !== "string") throw new TypeError("argument 'value' must be a string");
        adapter.setAttribute.call(this, attr, value);
    };
    button.prototype.removeAttribute = function(attr) {
        if (typeof attr !== "string") throw new TypeError("argument 'attr' must be a string");
        adapter.removeAttribute.call(this, attr);
    };
    button.setLoadingState = function(button, disableWhileLoading) {
        adapter.setLoadingState(button, disableWhileLoading);
    };
    button.removeLoadingState = function(button, mustDisable) {
        adapter.removeLoadingState(button, mustDisable);
    };
    button.setText = function(button, text) {
        if (!(button instanceof HTMLButtonElement)) throw new TypeError("argument 'button' must be an instance of HTMLButtonElement");
        if (typeof text !== "string") throw new TypeError("argument 'value' must be a string");
        adapter.setText(button, text);
    };

    module.exports = button;
}), null);

defineModule("IconButtonAdapter", ["EventType", "CSSCore", "Parent"], (function(global, requireModule, requireDynamic, requireLazy, module, exports, eventTypes) {
    'use strict';

    var classList = requireModule("components:classlist").icon_button;
    var ARIA_PRESSED = "aria-pressed";


    function toggleIcon(iconButton) {{
        if (requireModule("CSSCore").hasClass(iconButton, classList.on)) {
            requireModule("CSSCore").removeClass(iconButton, classList.on);
            iconButton.setAttribute(ARIA_PRESSED, "false");
        } else {
            requireModule("CSSCore").addClass(iconButton, classList.on);
            iconButton.setAttribute(ARIA_PRESSED, "true");
        }
    }}

    var eventHandler = {
        handleClickEvent: function(target, event) {
            var icons = requireModule("Children").byClass(target, classList.icon);
            var isToggleButton = false;

            icons && icons.forEach(function(icon) {
                if (requireModule("CSSCore").hasClass(icon, classList.icon_on)) {
                    isToggleButton = true;
                }
            });

            if (isToggleButton) {
                toggleIcon(target);
            }
        },
        handleOnChange: function() {
            var callback = this.onChangeCallback;
            var _this = this;
            requireModule("Event").addEventListener(this._root, eventTypes.MOUSE.CLICK, function(event) {
                var isOn = requireModule("CSSCore").hasClass(_this._root, classList.on);
                callback({
                    event: event,
                    isOn: isOn
                });
            });
        },
    };

    var adapter = {
        handleClickEvent: eventHandler.handleClickEvent,
        addClass: function(className) {
            requireModule("CSSCore").addClass(this._root, className);
        },
        removeClass: function(className) {
            requireModule("CSSCore").removeClass(this._root, className);
        },
        setDisabled: function(disabled) {
            this._root.disabled = disabled;
        },
        setAttribute: function(attr, value) {
            this._root.setAttribute(attr, value);
        },
        removeAttribute: function(attr) {
            this._root.removeAttribute(attr, value);
        },
        onChange: function() {
            eventHandler.handleOnChange.call(this);
        }
    };

    module.exports = adapter;
}), null);

defineModule("IconButton", ["IconButtonAdapter"], (function(global, requireModule, requireDynamic, requireLazy, module, exports, adapter) {
    'use strict';

    function iconButton(id) {
        this._root = requireModule("$").fromIDOrElement(id);
    }
    iconButton.prototype.addClass = function(className) {
        if (typeof className !== "string") throw new TypeError("argument 'className' must be a string");
        adapter.addClass.call(this, className);
    };
    iconButton.prototype.removeClass = function(className) {
        if (typeof className !== "string") throw new TypeError("argument 'className' must be a string");
        adapter.removeClass.call(this, className);
    };
    iconButton.prototype.hasClass = function(className) {
        if (typeof className !== "string") throw new TypeError("argument 'className' must be a string");
        adapter.removeClass.call(this, className);
    };
    iconButton.prototype.setDisabled = function(disabled) {
        if (typeof disabled !== "boolean") throw new TypeError("argument 'disabled' must be a boolean");
        adapter.setDisabled.call(this, disabled);
    };
    iconButton.prototype.setAttribute = function(attr, value) {
        if (typeof attr !== "string") throw new TypeError("argument 'attr' must be a string");
        if (typeof value !== "string") throw new TypeError("argument 'value' must be a string");
        adapter.setAttribute.call(this, attr, value);
    };
    iconButton.prototype.removeAttribute = function(attr) {
        if (typeof attr !== "string") throw new TypeError("argument 'attr' must be a string");
        adapter.removeAttribute.call(this, attr);
    };
    iconButton.prototype.onChange = function(callback) {
        if (typeof callback !== "function") throw new TypeError("argument 'callback' must be a function");
        this.onChangeCallback = callback;
        adapter.onChange.call(this);
    };

    module.exports = iconButton;
}), null);

defineModule("Ripple", ["CSSCore", "Parent"], (function(global, requireModule, requireDynamic, requireLazy, module, exports) {
    'use strict';
    var RIPPLE_TYPE_ATTR = "data-event";
    var RIPPLE_ANIMATION_DURATION = 650;
    var classList = requireModule("components:classlist")["ripple"];

    /**
     *
     * @param {MouseEvent} evt
     */
    function getHolder(evt) {
        var holder = evt.target || evt.srcElement;
        var length = holder.childNodes,length;

        if (holder.localName !== 'button' || !length) {
            return requireModule("CSSCore").hasClass(holder, classList['ripple']) ? holder : null;
        }

        for (var i = 0; i < length; i++) {
            var child = holder.childNodes[i];
            if (requireModule("CSSCore").hasClass(child, classList['ripple'])) {
                return child;
            }
        }
        return null;
    }


    function setUpPositionAndEvents(holder, evt, evtType) {
        var rect = holder.getBoundingClientRect();
        var x, y;
        x = evt.offsetX;
        if (typeof x !== "undefined") {
            y = evt.offsetY;
        } else {
            x = evt.clientX - rect.left;
            y = evt.clientY - rect.top;
        }
        var ripple = document.createElement("div");
        var max = (rect.width === rect.height) ?
            rect.width * 1.412 :
            Math.sqrt(rect.width * rect.width + rect.height * rect.height);

        var dim = max * 2 + "px";
        ripple.style.width  = dim;
        ripple.style.height = dim;
        ripple.style.marginLeft = -max + x + "px";
        ripple.style.marginTop  = -max + y + "px";

        requireModule("CSSCore").addClass(ripple, classList["child"]);
        holder.appendChild(ripple);
        setTimeout(function () {
            requireModule("CSSCore").addClass(ripple, classList["child_held"]);
        }, 0);

        var releaseEvent = (evtType === "mousedown" ? "mouseup" : "touchend");
        var release = function (e) {
            requireModule("Event").removeEventListener(document, releaseEvent, release);
            requireModule("CSSCore").addClass(ripple, classList["child_done"]);

            setTimeout(function () {
                holder.removeChild(ripple);
                if (!holder.children.length) {
                    requireModule("CSSCore").removeClass(holder, "active");
                    holder.removeAttribute(RIPPLE_TYPE_ATTR);
                }
            }, RIPPLE_ANIMATION_DURATION);
        };
        requireModule("Event").addEventListener(document, releaseEvent, release, true);
        return holder;
    }

    function start(evtType, evt) {
        var holder = getHolder(evt);
        if (!holder) return;

        var prev = holder.getAttribute(RIPPLE_TYPE_ATTR);
        if (prev && prev !== evtType) return false;

        holder.setAttribute(RIPPLE_TYPE_ATTR, evtType);
        setUpPositionAndEvents(holder, evt, evtType);
    }


    var adapter = {
        handleEvent: start
    };

    module.exports = adapter;
}), null);

defineModule("CheckboxAdapter", ["EventType", "CSSCore", "Parent", "Children"], (function(global, requireModule, requireDynamic, requireLazy, module, exports, eventTypes) {
    'use strict';

    var classList = requireModule("components:classlist").checkbox;

    function getCheckbox(checkboxNativeInput) {
        var checkbox  = requireModule("Parent").byClass(checkboxNativeInput, classList.container);
        var checkmark = requireModule("Children").byClass(checkbox, classList.checkmark)[0];

        return {
            checkbox: checkbox,
            input: checkboxNativeInput,
            checkmark: checkmark
        }
    }

    function addClass(className) {
        var checkbox = getCheckbox(this._root).checkbox;
        requireModule("CSSCore").addClass(checkbox, className);
    }

    function removeClass(className) {
        var checkbox = getCheckbox(this._root).checkbox;
        requireModule("CSSCore").removeClass(checkbox, className);
    }

    function setDisabled(checkboxInput, disabled) {
        var checkbox = getCheckbox(checkboxInput).checkbox;
        disabled ? requireModule("CSSCore").addClass(checkbox, classList.disabled)
                 : requireModule("CSSCore").removeClass(checkbox, classList.disabled);
        disabled ? checkboxInput.disabled = true : checkboxInput.disabled = false;
    }

    function hasNativeControl(checkboxInput) {
        return checkboxInput instanceof HTMLInputElement;
    }

    function setNativeControlAttr(attr, value) {
        this._root.setAttribute(attr, value);
    }

    function removeNativeControlAttr(attr) {
        if (this._root.hasAttribute(attr)) {
            this._root.removeAttribute(attr);
        }
    }

    function handleOnChange() {
        var callback = this.onChangeCallback;
        requireModule("Event").addEventListener(this._root, "change", function(event) {
            callback(event);
        });
    }
    
    function handleFocusEvent(checkboxControl, event) {
        var type = event.type;
        var checkbox = getCheckbox(checkboxControl).checkbox;

        type == eventTypes.FOCUSIN ? 
            requireModule("CSSCore").addClass(checkbox, classList.focused) : 
            requireModule("CSSCore").removeClass(checkbox, classList.focused);
    }

    var adapter = {
        handleFocusEvent: handleFocusEvent,
        addClass: function(className) {
            addClass.call(this, className);
        },
        removeClass: function(className) {
            removeClass.call(this, className);
        },
        setDisabled: function(disabled) {
            setDisabled(this._root, disabled);
        },
        hasNativeControl: function() {
            return hasNativeControl(this._root);
        },
        setNativeControlAttr: function(attr, value) {
            setNativeControlAttr.call(this, attr, value);
        },
        removeNativeControlAttr: function(attr) {
            removeNativeControlAttr.call(this, attr);
        },
        onChange: function() {
            handleOnChange.call(this);
        }
    };

    module.exports = adapter;
}), null);

defineModule("Checkbox", ["CheckboxAdapter"], (function(global, requireModule, requireDynamic, requireLazy, module, exports, adapter) {
    'use strict';

    function checkbox(id) {
        this._root = requireModule("$").fromIDOrElement(id);
    }
    /**
     * Adds a class to the root element.
     * @param {string} className
     */
    checkbox.prototype.addClass = function(className) {
        if (typeof className !== "string") throw new TypeError("argument 'className' must be a string");
        adapter.addClass.call(this, className)
    };
    /**
     * Removes a class from the root element.
     * @param {string} className
     */
    checkbox.prototype.removeClass = function(className) {
        if (typeof className !== "string") throw new TypeError("argument 'className' must be a string");
        adapter.removeClass.call(this, className);
    };
    /**
     * Returns true if the component is checked.
     */
    checkbox.prototype.isChecked = function() {
        return this._root.checked;
    };
    /**
     * Sets the checked state of the checkbox.
     * @param {boolean} checked
     */
    checkbox.prototype.setChecked = function(checked) {
        if (typeof checked !== "boolean") throw new TypeError("argument 'checked' must be a boolean");
        this._root.checked = checked;
    };
    /**
     * Sets the disabled state of the checkbox.
     * @param {boolean} disabled
     */
    checkbox.prototype.setDisabled = function(disabled) {
        if (typeof disabled !== "boolean") throw new TypeError("argument 'disabled' must be a boolean");
        adapter.setDisabled.call(this, disabled);
    };
    /**
     * Returns true if the input is present in the component.
     */
    checkbox.prototype.hasNativeControl = function() {
        return adapter.hasNativeControl.call(this);
    };
    /**
     * Sets an HTML attribute to the given value on the native input element.
     * @param {string} attr
     * @param {string} value
     */
    checkbox.prototype.setNativeControlAttr = function(attr, value) {
        if (typeof attr !== "string") throw new TypeError("argument 'attr' must be a string");
        if (typeof value !== "string") throw new TypeError("argument 'value' must be a string");
        adapter.setNativeControlAttr.call(this, attr, value);
    };
    /**
     * Removes an attribute from the native input element.
     * @param {string} attr
     */
    checkbox.prototype.removeNativeControlAttr = function(attr) {
        if (typeof attr !== "string") throw new TypeError("argument 'attr' must be a string");
        adapter.removeNativeControlAttr.call(this, attr);
    };
    /**
     * event handler that should be applied to the checkbox element.
     * @param {Function} callback
     */
    checkbox.prototype.handleChange = function(callback) {
        if (typeof callback !== "function") throw new TypeError("argument 'callback' must be a function");
        this.onChangeCallback = callback;
        adapter.onChange.call(this);
    };

    module.exports = checkbox;
}), null);

defineModule("RadioButtonAdapter", ["CSSCore", "Parent", "Children"], (function(global, requireModule, requireDynamic, requireLazy, module, exports) {
    'use strict';

    var classList = requireModule("components:classlist").radio;

    /**
     *
     * @param {HTMLInputElement} radioInput
     * @returns
     */
    function getRadioButton(radioInput) {
        var radio  = requireModule("Parent").byClass(radioInput, classList.container);

        return {
            radio: radio,
            input: radioInput
        }
    }

    function addClass(className) {
        var radio = getRadioButton(this._root).radio;
        requireModule("CSSCore").addClass(radio, className);
    }

    function removeClass(className) {
        var radio = getRadioButton(this._root).radio;
        requireModule("CSSCore").removeClass(radio, className);
    }

    function setDisabled(radioInput, disabled) {
        var radio = getRadioButton(radioInput).radio;
        disabled ? requireModule("CSSCore").addClass(radio, classList.disabled)
                 : requireModule("CSSCore").removeClass(radio, classList.disabled);
        disabled ? radioInput.disabled = true : radioInput.disabled = false;
    }

    function hasNativeControl(radioInput) {
        return radioInput instanceof HTMLInputElement;
    }

    function setNativeControlAttr(attr, value) {
        this._root.setAttribute(attr, value);
    }

    function removeNativeControlAttr(attr) {
        if (this._root.hasAttribute(attr)) {
            this._root.removeAttribute(attr);
        }
    }

    var eventHandler = {
        handleOnChange: function() {
            var callback = this.onChangeCallback;
            requireModule("Event").addEventListener(this._root, "change", function(event) {
                callback(event);
            });
        }
    };

    var adapter = {
        addClass: function(className) {
            addClass.call(this, className);
        },
        removeClass: function(className) {
            removeClass.call(this, className);
        },
        setDisabled: function(disabled) {
            setDisabled(this._root, disabled);
        },
        hasNativeControl: function() {
            return hasNativeControl(this._root);
        },
        setNativeControlAttr: function(attr, value) {
            setNativeControlAttr.call(this, attr, value);
        },
        removeNativeControlAttr: function(attr) {
            removeNativeControlAttr.call(this, attr);
        }
    };

    module.exports = {
        addClass: adapter.addClass,
        removeClass: adapter.removeClass,
        setDisabled: adapter.setDisabled,
        hasNativeControl: adapter.hasNativeControl,
        setNativeControlAttr: adapter.setNativeControlAttr,
        removeNativeControlAttr: adapter.removeNativeControlAttr,
        onChange: eventHandler.handleOnChange
    };
}), null);

defineModule("RadioButton", ["RadioButtonAdapter"], (function(global, requireModule, requireDynamic, requireLazy, module, exports, adapter) {
    'use strict';

    function radioButton(id) {
        this._root = requireModule("$").fromIDOrElement(id);
    }
    /**
     * Adds a class to the root element.
     * @param {string} className
     */
    radioButton.prototype.addClass = function(className) {
        if (typeof className !== "string") throw new TypeError("argument 'className' must be a string");
        adapter.addClass.call(this, className);
    };
    /**
     * Removes a class from the root element.
     * @param {string} className
     */
    radioButton.prototype.removeClass = function(className) {
        if (typeof className !== "string") throw new TypeError("argument 'className' must be a string");
        adapter.removeClass.call(this, className);
    };
    /**
     * Returns true if the component is checked.
     */
    radioButton.prototype.isChecked = function() {
        return this._root.checked;
    };
    /**
     * Sets the checked state of the checkbox.
     * @param {boolean} checked
     */
    radioButton.prototype.setChecked = function(checked) {
        if (typeof checked !== "boolean") throw new TypeError("argument 'checked' must be a boolean");
        this._root.checked = checked;
    };
    /**
     * Sets the disabled state of the checkbox.
     * @param {boolean} disabled
     */
    radioButton.prototype.setDisabled = function(disabled) {
        if (typeof disabled !== "boolean") throw new TypeError("argument 'disabled' must be a boolean");
        adapter.setDisabled.call(this, disabled);
    };
    /**
     * Returns true if the input is present in the component.
     */
    radioButton.prototype.hasNativeControl = function() {
        return adapter.hasNativeControl.call(this);
    };
    /**
     * Sets an HTML attribute to the given value on the native input element.
     * @param {string} attr
     * @param {string} value
     */
    radioButton.prototype.setNativeControlAttr = function(attr, value) {
        if (typeof attr !== "string") throw new TypeError("argument 'attr' must be a string");
        if (typeof value !== "string") throw new TypeError("argument 'value' must be a string");
        adapter.setNativeControlAttr.call(this, attr, value);
    };
    /**
     * Removes an attribute from the native input element.
     * @param {string} attr
     */
    radioButton.prototype.removeNativeControlAttr = function(attr) {
        if (typeof attr !== "string") throw new TypeError("argument 'attr' must be a string");
        adapter.removeNativeControlAttr.call(this, attr);
    };
    /**
     * event handler that should be applied to the checkbox element.
     * @param {Function} callback
     */
    radioButton.prototype.handleChange = function(callback) {
        if (typeof callback !== "function") throw new TypeError("argument 'callback' must be a function");
        this.onChangeCallback = callback;
        adapter.onChange.call(this);
    };

    module.exports = radioButton;
}), null);

defineModule("ChipSetAdapter", ["EventType", "CSSCore"], (function(global, requireModule, requireDynamic, requireLazy, module, exports, eventTypes) {
    'use strict';

    var classList = requireModule("components:classlist").chip;

    function setSelectedState(chip, selected) {
        selected ? requireModule("CSSCore").addClass(chip, classList.chip_selected)
                 : requireModule("CSSCore").removeClass(chip, classList.chip_selected);
    }
    
    function setSelectedStateToggle(chip, selected) {
        var chipset = requireModule("Parent").byClass(chip, classList.filter);
        if (chipset) {
            // Hidding leading icon (if exists)
            var leadingIcon = requireModule("Child").byClass(chip, classList.chip_icon_leading);
            if (leadingIcon) {
                requireModule("CSSCore").toggleClass(leadingIcon, classList.chip_icon_leading_hidden);
            }
        }
        requireModule("CSSCore").toggleClass(chip, classList.chip_selected);
    }

    function getChipset(chipItem) {
        var chipset = requireModule("Parent").byClass(chipItem, classList.chipset);
        var chips = requireModule("Children").byClass(chipset, classList.chip);

        return {
            chipset: chipset,
            chips: chips
        }
    }

    function select(targetChip) {
        var chipsetData = getChipset(targetChip);
        var chipset = chipsetData.chipset;
        var chips = chipsetData.chips;

        if (chipset == null) return;
        
        var isMultipleSelect = requireModule("CSSCore").hasClass(chipset, classList.filter);

        if (isMultipleSelect) {
            setSelectedStateToggle(targetChip);
        } else {
            for (var i = 0; i < chips.length; i++) {
                var chip = chips[i];
                setSelectedState(chip, false);
            }
            setSelectedState(targetChip, true);
        }
    }

    /**
     * Handle onchange event to each chip
     * @param {Function} callback
     * @returns
     */
    function onChange(callback) {
    }

    
    var adapter = {
        addClass: function(className) {
            requireModule("CSSCore").addClass(this._root, className);
        },
        removeClass: function(className) {
            requireModule("CSSCore").removeClass(this._root, className);
        },
        getChipIndexByID: function(chipID) {
            var chipsetData = getChipset(this._root);
            var chips = chipsetData.chips;
            if (!chips || !chips.length) return -1;

            for (var i = 0; i < chips.length; i++) {
                // Case 1: chip is a DOM element with id attribute
                if (chips[i] && chips[i].id === chipID) {
                    return i;
                }
            }

            return -1; // Not found
        },
        getChipIdAtIndex: function(index) {
            var chipsetData = getChipset(this._root);
            var chips = chipsetData.chips;

            if (!chips || index < 0 || index >= chips.length) {
                return null;
            }

            var chip = chips[index];

            // Case 1: chip is a DOM element with an id attribute
            if (chip && chip.id) {
                return chip.id;
            }

            return null; // no id available
        },
        getSelectedChipIndexes: function() {
            var chipsetData = getChipset(this._root);
            var chips = chipsetData.chips;
            var selectedIndexes = [];

            if (!chips || !chips.length) return selectedIndexes;

            for (var i = 0; i < chips.length; i++) {
                var chip = chips[i];
                if (chip && requireModule("CSSCore").hasClass(chip, classList.chip_selected)) {
                    selectedIndexes.push(i);
                }
            }

            return selectedIndexes;
        },
        getSelectedChipIds: function() {
            var chipsetData = getChipset(this._root);
            var chips = chipsetData.chips;
            var selectedIds = [];

            if (!chips || !chips.length) return selectedIds;

            for (var i = 0; i < chips.length; i++) {
                var chip = chips[i];
                if (chip && requireModule("CSSCore").hasClass(chip, classList.chip_selected)) {
                    if (chip.id) {
                        selectedIds.push(chip.id);
                    }
                }
            }

            return selectedIds;
        },
        setChipSelected: function(index, isSelected) {
            var chipsetData = getChipset(this._root);
            var chipset = chipsetData.chipset;
            var chips = chipsetData.chips;

            if (!chips || index < 0 || index >= chips.length) {
                return; // invalid index
            }

            var chip = chips[index];
            if (!chip || !chip.classList) {
                return; // not a valid DOM element
            }

            var isMultipleSelect = requireModule("CSSCore").hasClass(chipset, classList.filter);

            if (isMultipleSelect) {
                setSelectedStateToggle(chip);
            } else {
                for (var i = 0; i < chips.length; i++) {
                    var currentChip = chips[i];
                    setSelectedState(currentChip, false);
                }
                setSelectedState(chip, true);
            }
            
        },
        isChipSelected: function(index) {
            var chipsetData = getChipset(this._root);
            var chips = chipsetData.chips;

            if (!chips || index < 0 || index >= chips.length) {
                return false; // invalid index
            }

            var chip = chips[index];
            if (!chip || !chip.classList) {
                return false;
            }

            return requireModule("CSSCore").hasClass(chip, classList.chip_selected);
        },
        getChipCount: function() {
            var chipsetData = getChipset(this._root);
            return chipsetData.chips.length || -1;
        },
        removeChip: function(index) {
            var chipsetData = getChipset(this._root);
            var chips = chipsetData.chips;

            if (!chips || index < 0 || index >= chips.length) {
                return false; // invalid index
            }

            var chip = chips[index];
            if (!chip || !chip.classList) {
                return false;
            }

            // No necessary to call chips.splice;
            var container = chip.parentNode;
            container.removeChild(chip);
        }
    };

    var eventHandler = {
        handleClickEvent: function(target, event) {
            select(target);
        },
        handleRemoveIconClickEvent: function(target, event) {
            var chip = target.parentNode;
            var chipContainer = chip.parentNode;

            if (chip && chipContainer.hasChildNodes()) {
                chipContainer.removeChild(chip);
            }
        }
    };

    module.exports = {
        addClass: adapter.addClass,
        removeClass: adapter.removeClass,
        getChipIndexByID: adapter.getChipIndexByID,
        getChipIdAtIndex: adapter.getChipIdAtIndex,
        getSelectedChipIndexes: adapter.getSelectedChipIndexes,
        getSelectedChipIds: adapter.getSelectedChipIds,
        setChipSelected: adapter.setChipSelected,
        isChipSelected: adapter.isChipSelected,
        getChipCount: adapter.getChipCount,
        removeChip: adapter.removeChip,
        handleClickEvent: eventHandler.handleClickEvent,
        handleRemoveIconClickEvent: eventHandler.handleRemoveIconClickEvent
    };
}), null);

defineModule("ChipSet", ["ChipSetAdapter"], (function (global, requireModule, requireDynamic, requireLazy, module, exports, adapter) {
    'use strict';

    function chipset(id) {
        this._root = requireModule("$").fromIDOrElement(id);
    }
    chipset.prototype.addClass = function(className) {
        if (typeof className !== "string") throw new TypeError("argument 'className' must be a string type");
        adapter.addClass.call(this, className);
    };
    chipset.prototype.removeClass = function(className) {
        if (typeof className !== "string") throw new TypeError("argument 'className' must be a string type");
        adapter.removeClass.call(this, className);
    };
    chipset.prototype.getChipIndexByID = function(chipID) {
        if (typeof chipID !== "string") throw new TypeError("argument 'chipID' must be a string type");
        return adapter.getChipIndexByID.call(this, chipID);
    };
    chipset.prototype.getChipIdAtIndex = function(index) {
        if (typeof index !== "number") throw new TypeError("argument 'index' must be a number type");
        return adapter.getChipIdAtIndex.call(this, index);
    };
    chipset.prototype.getSelectedChipIndexes = function() {
        return adapter.getSelectedChipIndexes.call(this);
    };
    chipset.prototype.getSelectedChipIds = function() {
        return adapter.getSelectedChipIds.call(this);
    };
    chipset.prototype.setChipSelected = function(index, isSelected) {
        if (typeof index !== "number") throw new TypeError("argument 'index' must be a number type");
        if (typeof isSelected !== "boolean") throw new TypeError("argument 'isSelected' must be a boolean type");
        adapter.setChipSelected.call(this, index, isSelected);
    };
    chipset.prototype.isChipSelected = function(index) {
        if (typeof index !== "number") throw new TypeError("argument 'index' must be a number type");
        return adapter.isChipSelected.call(this, index);
    };
    chipset.prototype.getChipCount = function() {
        return adapter.getChipCount.call(this);
    };
    chipset.prototype.addChip = function(index, chip) {
        
    };
    chipset.prototype.removeChip = function(index) {
        if (typeof index !== "number") throw new TypeError("argument 'index' must be a number type");
        return adapter.removeChip.call(this, index);
    };
    chipset.prototype.onAction = function(callback) {
        if (typeof callback !== "function") throw new TypeError("argument 'callback' must be a function type");
        this.onActionCallback = callback;
    };

    module.exports = chipset;
}), null);

defineModule("TabScrollAdapter", [], (function(global, requireModule, requireDynamic, requireLazy, module, exports) {
    'use strict';
    
    var classList = requireModule("components:classlist").tab;
    
    /**
     * Check if content overflow requires scrolling
     */
    function checkScrollNeeded(scrollArea, scrollContent) {
        if (!scrollArea || !scrollContent) return false;
        
        var contentWidth = scrollContent.scrollWidth;
        var areaWidth = scrollArea.clientWidth;
        
        if (contentWidth > areaWidth) {
            scrollArea.classList.add(classList.scroller_area + '--scroll');
            return true;
        } else {
            scrollArea.classList.remove(classList.scroller_area + '--scroll');
            return false;
        }
    }
    
    /**
     * Apply momentum scrolling effect
     */
    function applyMomentum(scrollArea, velocity) {
        var momentum = velocity * 200;
        var targetScroll = scrollArea.scrollLeft - momentum;
        
        targetScroll = Math.max(0, Math.min(targetScroll, scrollArea.scrollWidth - scrollArea.clientWidth));
        
        scrollArea.scrollTo({
            left: targetScroll,
            behavior: 'smooth'
        });
    }

    /**
     * Scroll to active tab
     */
    function scrollToTab(tab) {
        var tabBar = requireModule("Parent").byClass(tab, classList.tabbar);
        if (!tabBar) return;
        
        var scrollArea = tabBar.querySelector('[data-scroll-area]');
        if (!scrollArea) return;

        var tabRect = tab.getBoundingClientRect();
        var scrollRect = scrollArea.getBoundingClientRect();

        var tabCenter = tabRect.left + (tabRect.width / 2);
        var scrollCenter = scrollRect.left + (scrollRect.width / 2);

        var scrollOffset = tabCenter - scrollCenter;

        scrollArea.scrollBy({
            left: scrollOffset,
            behavior: 'smooth'
        });
    }
    
    /**
     * Get scroll area from any element within tab bar
     */
    function getScrollArea(element) {
        var tabBar = requireModule("Parent").byClass(element, classList.tabbar);
        if (!tabBar) return null;
        
        return tabBar.querySelector('[data-scroll-area]');
    }
    
    /**
     * Get scroll content from scroll area
     */
    function getScrollContent(scrollArea) {
        if (!scrollArea) return null;
        return scrollArea.querySelector('[data-scroll-content]');
    }
    
    module.exports = {
        checkScrollNeeded: checkScrollNeeded,
        applyMomentum: applyMomentum,
        scrollToTab: scrollToTab,
        getScrollArea: getScrollArea,
        getScrollContent: getScrollContent
    };
}), null);

defineModule("TabScrollState", [], (function(global, requireModule, requireDynamic, requireLazy, module, exports) {
    'use strict';

    // Store scroll state for each scroll area using WeakMap
    var scrollStates = new WeakMap();
    
    function getState(scrollArea) {
        if (!scrollStates.has(scrollArea)) {
            scrollStates.set(scrollArea, {
                isScrolling: false,
                startX: 0,
                scrollLeft: 0,
                velocity: 0,
                lastX: 0,
                lastTime: 0
            });
        }
        return scrollStates.get(scrollArea);
    }
    
    function setState(scrollArea, updates) {
        var state = getState(scrollArea);
        Object.assign(state, updates);
    }
    
    function resetState(scrollArea) {
        setState(scrollArea, {
            isScrolling: false,
            startX: 0,
            scrollLeft: 0,
            velocity: 0,
            lastX: 0,
            lastTime: 0
        });
    }
    
    module.exports = {
        getState: getState,
        setState: setState,
        resetState: resetState
    };
}), null);

defineModule("TabAdapter", ["EventType", "Child", "Children", "CSSCore", "Event", "KeyboardEvent", "Parent"], (function(global, requireModule, requireDynamic, requireLazy, module, exports, eventTypes) {
    'use strict';
    
    var classList = requireModule("components:classlist").tab;

    var ARIA_SELECTED = "aria-selected";
    var TAB_INDEX = "tabindex";
    var FOCUSED_TAB = "data-focused-tab-index";
    
    /**
     * Animates the indicator from the previous tab's position to the new tab's position.
     * @param {HTMLButtonElement} previousTab The previously active tab.
     * @param {HTMLButtonElement} newTab The newly selected tab.
     */
    function changeIndicatorPosition(previousTab, newTab) {
        var newIndicator = getIndicator(newTab);
        var newIndicatorContent = getIndicatorContent(newIndicator);

        var prevRect = previousTab.getBoundingClientRect();
        var newRect = newTab.getBoundingClientRect();

        var scaleX = prevRect.width / newRect.width;
        var translateX = prevRect.left - newRect.left;
        requireModule("CSSCore").addClass(newIndicator, classList.no_transition);
        newIndicatorContent.style.transform = `translateX(${translateX}px) scaleX(${scaleX})`;

        newIndicator.getBoundingClientRect();

        requireModule("CSSCore").removeClass(newIndicator, classList.no_transition);
        newIndicatorContent.style.transform = '';

        var onTransitionEnd = function(event) {
            if (event.propertyName === 'transform') {
                newIndicatorContent.style.transform = '';
                requireModule("Event").removeEventListener(newIndicatorContent, 'transitionend', onTransitionEnd);
            }
        };
        requireModule("Event").addEventListener(newIndicatorContent, 'transitionend', onTransitionEnd);
    }
    
    function showTabPane(tab, tabPaneId) {
        var tabPane = requireModule("$").fromIDOrElement(tabPaneId);
        var tabPaneContainer = tabPane.parentNode;
        
        if (!tabPaneContainer) return;

        var tabPanes = requireModule("Children").byClass(tabPaneContainer, classList.pane);
        if (!tabPanes) return;
        
        tabPanes.forEach(function(pane) {
            pane.setAttribute("hidden", true);
        });
        tabPane.removeAttribute("hidden");
    }

    /**
     * Handles the primary logic for switching tabs, including the initial selection.
     * @param {HTMLButtonElement} newTab The tab that was just selected.
     */
    function changeTab(newTab, callback) {
        var tabbar = getTabBar(newTab);
        var previousTab = tabbar.querySelector('[role="tab"][aria-selected="true"]');

        // If the clicked tab is already the active one, do nothing.
        if (previousTab === newTab) {
            return;
        }

        // If there is a previously selected tab, deactivate it and animate the indicator.
        if (previousTab) {
            // Animate the indicator from the old position to the new one.
            changeIndicatorPosition(previousTab, newTab);
            // Deactivate the old tab.
            setActiveState(previousTab, false);
        }
        
        // Activate the newly selected tab.
        // This runs on the first click (when previousTab is null) and all subsequent clicks.
        setActiveState(newTab, true);

        requireModule("TabScrollAdapter").scrollToTab(newTab);
        
        if (typeof callback === "function") {
            callback();
        }

        // Does the tab has an pane associated with aria-controls?
        if (newTab.hasAttribute("aria-controls")) {
            showTabPane(newTab, newTab.getAttribute("aria-controls"));
        }
    }

    function navigateBetween(tab, direction) {
        var tabbar = getTabBar(tab);
        var tabs = getTabs(tabbar);

        var currentIndex = parseInt(tabbar.getAttribute(FOCUSED_TAB), 10) || 0;
        var nextIndex;

        if (direction === "left") {
            nextIndex = currentIndex - 1;
            if (nextIndex < 0) {
                nextIndex = tabs.length - 1;
            }
        } else if (direction === "right") {
            nextIndex = currentIndex + 1;
            if (nextIndex >= tabs.length) {
                nextIndex = 0;
            }
        }

        for (var i = 0; i < tabs.length; i++) {
            requireModule("CSSCore").removeClass(tabs[i], classList.focused);
        }

        var nextTab = tabs[nextIndex];

        if (nextTab) {
            requireModule("CSSCore").addClass(nextTab, classList.focused);
            nextTab.focus();
        }
    }

    /**
     * Sets the active or inactive state for a tab and its indicator.
     * @param {HTMLButtonElement} tab The tab to modify.
     * @param {boolean} active Whether the tab should be active.
     */
    function setActiveState(tab, active) {
        tab.setAttribute(ARIA_SELECTED, active ? "true" : "false");
        tab.setAttribute(TAB_INDEX, active ? "0" : "-1");

        var indicator = getIndicator(tab);
        var indicatorContent = getIndicatorContent(indicator);
        var tabContent = getTab(tab);

        var label = tabContent.label;
        var icon = tabContent.icon;

        if (tabContent.isPrimaryTabBar()) {
            if (label !== null) {
                indicatorContent.style.width = label.offsetWidth + 'px';
            }
        }


        if (active) {
            requireModule("CSSCore").addClass(tab, classList.active);
            requireModule("CSSCore").addClass(indicator, classList.indicator_active);
        } else {
            requireModule("CSSCore").removeClass(tab, classList.active);
            requireModule("CSSCore").removeClass(indicator, classList.indicator_active);
        }
    }

    function getTab(tab) {
        var content = requireModule("Child").byClass(tab, classList.content);
        var icon = requireModule("Child").byClass(content, classList.icon);
        var label = requireModule("Child").byClass(content, classList.label);
        var badge = requireModule("Child").byClass(content, classList.badge);

        return {
            isPrimaryTabBar: function() {
                if (content == null) return false;
                var tabBar = requireModule("Parent").byClass(content, classList.tabbar);
                return requireModule("CSSCore").hasClass(tabBar, classList.primary);
            },
            content: content,
            icon: icon,
            label: label,
            badge: badge
        }
    }

    function getTabBar(tab) {
        return requireModule("Parent").byClass(tab, classList.tabbar);
    }

    function getTabs(tabbar) {
        return requireModule("Children").byClass(tabbar, classList.container);
    }

    function getIndicator(tab) {
        return requireModule("Child").byClass(tab, classList.indicator);
    }

    function getIndicatorContent(indicator) {
        return indicator.querySelector('.' + classList.indicator_content);
    }
    
    function setBadgeCount(targetTab, count) {
        var tab = getTab(targetTab);
        var badge = new(requireModule("Badge"))(count);

        if (tab.badge == null) {
            tab.content.appendChild(badge._root);
        }
    }

    var eventHandler = {
        handleClickEvent: function(target, event) {
            changeTab(target);
        },
        handleFocusEvent: function(tab, event) {
            var tabbar = getTabBar(tab);
            var tabs = getTabs(tabbar);
            tabbar.setAttribute(FOCUSED_TAB, tabs.indexOf(tab));

            if (eventTypes && event.type === eventTypes.FOCUSIN) {
                requireModule("Event").addEventListener(tab, 'keydown', eventHandler.handleKeyboardEvent);
            } else {
                requireModule("CSSCore").removeClass(tab, classList.focused);
                requireModule("Event").removeEventListener(tab, 'keydown', eventHandler.handleKeyboardEvent);
            }
        },
        handleKeyboardEvent: function(event) {
            var tab = event.target;

            if (requireModule("KeyboardEvent").matchKeys(37, "ArrowLeft", event)) {
                event.preventDefault();
                navigateBetween(tab, "left");
            }

            if (requireModule("KeyboardEvent").matchKeys(39, "ArrowRight", event)) {
                event.preventDefault();
                navigateBetween(tab, "right");
            }

            if (requireModule("KeyboardEvent").matchKeys(13, "Enter", event)) {
                event.preventDefault();
                changeTab(tab);
            }
        },
        handleInputEvent: function(event) {}
    };

    var adapter = {
        setActiveTab: function(index) {
            var tabbar = getTabBar(this._root);
            var tabs = getTabs(tabbar);
            var activeTab = tabs[index];
            changeTab(activeTab);
        },
        deactivateTabAtIndex: function(index) {
            var tabbar = getTabBar(this._root);
            var tabs = getTabs(tabbar);
            var activeTab = tabs[index];
            setActiveState(activeTab, false);
        },
        getTabListLength: function() {
            var tabbar = getTabBar(this._root);
            var tabs = getTabs(tabbar);
            return tabs.length;
        },
        setBadgeCount: function(index, count) {
            var tabbar = getTabBar(this._root);
            var tabs = getTabs(tabbar);
            var activeTab = tabs[index];
            setBadgeCount(activeTab, count);
        },
        onTabChange: function() {
            var tabbar = getTabBar(this._root);
            var tabs = getTabs(tabbar);
            
            var callback = this.onChangeCallback;
            
            tabs.forEach(function(tab) {
                requireModule("Event").addEventListener(tab, requireModule("EventType").MOUSE.CLICK, function(event) {
                    if (callback == null) return;
                    callback(tab, tabs.indexOf(tab));
                });
            });
        }
    };

    module.exports = {
        handleClickEvent: eventHandler.handleClickEvent,
        handleFocusEvent: eventHandler.handleFocusEvent,
        handleKeyboardEvent: eventHandler.handleKeyboardEvent,
        handleInputEvent: eventHandler.handleInputEvent,
        setActiveTab: adapter.setActiveTab,
        deactivateTabAtIndex: adapter.deactivateTabAtIndex,
        getTabListLength: adapter.getTabListLength,
        setBadgeCount: adapter.setBadgeCount,
        onTabChange: adapter.onTabChange
    };
}), null);

defineModule("TabBar", ["TabAdapter"], function(global, requireModule, requireDynamic, requireLazy, module, exports, adapter) {
    'use strict';

    /**
     *
     * @param {string} id
     */
    function tabbar(id) {
        this._root = requireModule("$").fromIDOrElement(id);
    }
    /**
     * Sets the tab at the given index to be activated.
     * @param {number} index
     */
    tabbar.prototype.setActiveTab = function(index) {
        if (typeof index !== "number") throw new TypeError("argument 'index' must be a number");
        adapter.setActiveTab.call(this, index);
    };
    /**
     * Deactivates the Tab at the given index.
     * @param {number} index
     */
    tabbar.deactivateTabAtIndex = function(index) {
        if (typeof index !== "number") throw new TypeError("argument 'index' must be a number");
        adapter.deactivateTabAtIndex.call(this, index);
    };
    /**
     * Returns the number of child Tab components.
     * @returns {Array<adapter.Tab>}
     */
    tabbar.prototype.getTabListLength = function() {
        return adapter.getTabListLength.call(this);
    };
    
    tabbar.prototype.setBadgeCount = function(index, count) {
        if (typeof index !== "number") throw new TypeError("argument 'index' must be a number");
        if (typeof count !== "number") throw new TypeError("argument 'count' must be a number");
        adapter.setBadgeCount.call(this, index, count);
    };

    tabbar.prototype.onTabChange = function(callback) {
        if (typeof callback !== "function") throw new TypeError("argument 'callback' must be a function");
        this.onChangeCallback = callback;
        adapter.onTabChange.call(this);
    };

    module.exports = tabbar;
}, null);

defineModule("ListAdapter", ["EventType", "Child", "Children", "CSSCore", "Event", "KeyboardEvent", "Parent"], (function (global, requireModule, requireDynamic, requireLazy, module, exports, eventTypes) {
    'use strict';

    var listClassList = requireModule("components:classlist").list;
    var selectClassList = requireModule("components:classlist").select;

    /**
     * Retrieves all focusable items within a list, excluding disabled ones.
     */
    function getFocusableItems(list) {
        var allItems = requireModule("Children").byClass(list, listClassList.item);
        return Array.from(allItems).filter(function(item) {
            return !requireModule("CSSCore").hasClass(item, listClassList.disabled);
        });
    }

    /**
     * Sets focus on a list item and updates the tabindex for all other items.
     */
    function setFocus(itemToFocus, allItems) {
        allItems.forEach(function(item) {
            item.setAttribute('tabindex', item === itemToFocus ? '0' : '-1');
        });
        itemToFocus.focus();
    }

    /**
     * Handles keyboard navigation for moving focus within the list.
     */
    function navigateBetween(currentItem, direction) {
        var list = requireModule("Parent").byClass(currentItem, listClassList.container);
        var items = getFocusableItems(list);
        if (items.length === 0) return;

        var currentIndex = items.indexOf(currentItem);
        var nextIndex = currentIndex;

        switch (direction) {
            case 'up': nextIndex = currentIndex > 0 ? currentIndex - 1 : items.length - 1; break;
            case 'down': nextIndex = currentIndex < items.length - 1 ? currentIndex + 1 : 0; break;
            case 'home': nextIndex = 0; break;
            case 'end': nextIndex = items.length - 1; break;
        }
        
        var parentItem = requireModule("Parent").byClass(currentItem, listClassList.with_collapsed_list);
        if (parentItem == null) {
            setFocus(items[nextIndex], items);
        }
    }
    
    /**
     * NEW: Checks if a list item contains an input control.
     * @param {HTMLElement} listItem The list item to check.
     * @returns {boolean}
     */
    function hasInput(listItem) {
        var meta = requireModule("Child").byClass(listItem, listClassList.meta);
        var input = meta ? meta.querySelector('input[type="checkbox"], input[type="radio"]') : null;
        return input !== null;
    }


    /**
     * Syncs the ARIA state of a list item with its underlying input.
     * This is the definitive function for updating accessibility attributes.
     * @param {HTMLElement} listItem The list item to sync.
     */
    function syncAriaState(listItem) {
        var meta = requireModule("Child").byClass(listItem, listClassList.meta);
        var input = meta ? meta.querySelector('input[type="checkbox"], input[type="radio"]') : null;
        
        if (input) {
            // The core of the fix: always sync aria-checked with the input's state.
            listItem.setAttribute('aria-checked', String(input.checked));
        }
    }

    /**
     * Enforces single-selection behavior for simple list items (acting like a radio group).
     * @param {HTMLElement} targetItem The item that should be activated.
     * @param {Array<HTMLElement>} allItems All focusable items in the list.
     */
    function handleSingleSelection(targetItem, allItems) {
        allItems.forEach(function(item) {
            requireModule("CSSCore").removeClass(item, listClassList.activated);
            if (hasInput(targetItem)) {
                item.setAttribute('aria-checked', "false");
            }
        });

        requireModule("CSSCore").addClass(targetItem, listClassList.activated);

        if (hasInput(targetItem)) {
            targetItem.setAttribute('aria-checked', "true");
        }
    }

    /**
     * Handles expanding and collapsing an inner list by simply toggling a class.
     * CSS now handles the animation via the grid-template-rows property.
     * @param {HTMLElement} listItem The parent list item that was clicked.
     * @param {HTMLElement} list The inner list to expand/collapse.
     */
    function handleInnerList(listItem, list) {
        var collapsedClass = listClassList.inner_list_collapsed;
        var withCollapsedClass = listClassList.with_collapsed_list;
        var isCollapsed = requireModule("CSSCore").hasClass(list, collapsedClass);
        var icon = listItem.querySelector('[data-action]');

        // Toggle the state classes for the list and the parent item.
        requireModule("CSSCore").conditionalToggleClass(list, collapsedClass, !isCollapsed);
        requireModule("CSSCore").conditionalToggleClass(listItem, withCollapsedClass, !isCollapsed);

        // Update the icon based on the new state.
        if (icon) {
            if (!isCollapsed) {
                // It is now collapsing
                icon.textContent = 'expand_more';
                icon.dataset.action = 'expand';
            } else {
                // It is now expanding
                icon.textContent = 'expand_less';
                icon.dataset.action = 'collapse';
            }
        }
    }

    var eventHandler = {
        handleClickEvent: function(listItem, event) {
            var list = requireModule("Parent").byClass(listItem, listClassList.container);
            if (requireModule("CSSCore").hasClass(list, listClassList.non_interactive)) return;

            var items = getFocusableItems(list);
            var meta = requireModule("Child").byClass(listItem, listClassList.meta);
            var input = meta ? meta.querySelector('input[type="checkbox"], input[type="radio"]') : null;

            // This is a real user click.
            setFocus(listItem, items);

            if (!input) {
                // This is a simple list item or a link. Run the single-selection logic.
                if (!requireModule("CSSCore").hasClass(list, listClassList.no_selection)) {
                    handleSingleSelection(listItem, items);
                }
            } else if (event.target === input) {
                // The user clicked the native input directly. The browser handled the state change.
                // We just need to sync the ARIA state.
                syncAriaState(listItem);
            } else {
                // User clicked the container, determine if it's a switch or a standard input

                var switchContainer = requireModule("Parent").byClass(input, requireModule("components:classlist").switch.container);

                if (switchContainer) {
                    // If the input belongs to a switch, delegate to the SwitchAdapter.
                    // It knows how to handle the state and the UI updates correctly.
                    requireModule("SwitchAdapter").handleClickEvent(input, event);
                } else {
                    // It's a standard radio or checkbox, so handle it directly.
                    if (input.type === 'radio') {
                        if (!input.checked) {
                            input.checked = true;
                        }
                    } else { // For checkboxes
                        input.checked = !input.checked;
                    }
                    syncAriaState(listItem);
                    var changeEvent = new Event('change', { bubbles: true });
                    input.dispatchEvent(changeEvent);
                }
            }

            // Handling inner lists (this logic remains the same)
            var innerList = requireModule("Sibling").byClass(listItem, listClassList.inner_list);
            if (innerList) {
                handleInnerList(listItem, innerList);
            }
        },
        handleFocusEvent: function(listItem, event) {
            var list = requireModule("Parent").byClass(listItem, listClassList.container);
            if (requireModule("CSSCore").hasClass(list, listClassList.non_interactive)) return;
            
            var isListFromSelectMenu = false;

            if (eventTypes && event.type == eventTypes.FOCUSIN) {
                requireModule("Event").addEventListener(listItem, 'keydown', eventHandler.handleKeyboardEvent);
            } else {
                requireModule("Event").removeEventListener(listItem, 'keydown', eventHandler.handleKeyboardEvent);
            }
        },
        handleKeyboardEvent: function(event) {
            var listItem = event.target;
            var list = requireModule("Parent").byClass(listItem, listClassList.container);
            var key = event.key;

            if (listItem.tagName === 'A' && key === 'Enter') {
                return; // Let the browser handle navigation for links.
            }

            switch (key) {
                case 'ArrowUp': navigateBetween(listItem, "up"); break;
                case 'ArrowDown': navigateBetween(listItem, "down"); break;
                case 'Home': navigateBetween(listItem, "home"); break;
                case 'End': navigateBetween(listItem, "end"); break;
                case ' ': // Space key
                case 'Enter': 
                    var input = listItem.querySelector('input');
                    if (input) {
                        input.click(); // Programmatically click to trigger all behaviors.
                    } else {
                        var innerList = requireModule("Sibling").byClass(listItem, listClassList.inner_list);
                        if (innerList) {
                            handleInnerList(listItem, innerList);
                        }
                    
                        if (requireModule("CSSCore").hasClass(list, listClassList.no_selection)) return;
                        // For simple items, enforce single selection logic.

                        var list = requireModule("Parent").byClass(listItem, listClassList.container);
                        var items = getFocusableItems(list);
                        handleSingleSelection(listItem, items);
                        
                        var parentSelect = requireModule("Parent").byClass(list, selectClassList.container);
                        if (parentSelect) {
                            requireModule("SelectAdapter").handleKeyboardEvent(listItem, event, true);
                        }
                    }
                    break;
                default: return; 
            }
            event.preventDefault(); 
        },
    };
    module.exports = {
        handleClickEvent: eventHandler.handleClickEvent,
        handleFocusEvent: eventHandler.handleFocusEvent
    };
}), null);

defineModule("TextfieldAdapter", ["Child", "Children", "CSSCore"], (function(global, requireModule, requireDynamic, requireLazy, module, exports) {
    'use strict';
    var classList = requireModule("components:classlist")["textfield"];
    var FLOATIING_LABEL_PREFIX = "-floating-label";
    var SUPPORTING_TEXT_PREFIX = "-supporting-text";
    
    var inputTypesWithPicker = ["date", "month", "week", "time", "datetime-local", "color", "file"];

    function hasDialogPicker(type) {
        return inputTypesWithPicker.indexOf(type) > -1;
    }

    function handleRequiredTextfieldInput(textfieldInput, eventType) {
        // Shaking floating label if text input it's empty
        if (eventType == "focusout") {
            if (textfieldInput.value.length === 0) {
                setErrorState(textfieldInput, true);
            } else {
                setErrorState(textfieldInput, false);
            }
        }
    }

    function handlePatternTextfieldInput(textfieldInput, eventType) {
        // If pattern is not valid, add error class to textfield
        if (eventType == "focusout") {
            var pattern = new RegExp(textfieldInput.getAttribute("pattern"));
            if (!pattern.test(textfieldInput.value)) {
                shakeFloatingLabel(getTextfield(textfieldInput).floatingLabel);
                setErrorState(textfieldInput, true);
            } else {
                setErrorState(textfieldInput, false);
            }
        }
    }

    function matchTailingIconAction(trailingIcon, action, callback) {
        var data = "data-icon";
        if (trailingIcon.hasAttribute(data) && trailingIcon.getAttribute(data) == action) {
            return true;
        }
        return false;
    }

    function setTrailingIconAction(trailingIcon, action) {
        var data = "data-icon";
        trailingIcon.setAttribute(data, action);
        trailingIcon.innerText = action;
    }

    function setFocusedState(textfield, focused) {
        if (focused) {
            requireModule("CSSCore").addClass(textfield, classList.focused);
            // Checking if textfield has not floating label
            if (!requireModule("CSSCore").hasClass(textfield, classList.no_label)) {
                addFloatingClass(textfield);
            }
        } else {
            requireModule("CSSCore").removeClass(textfield, classList.focused);
            removeFloatingClass(textfield);
        }
    }

    function addFloatingClass(textfield) {
        requireModule("CSSCore").addClass(textfield, classList.label_floating);
    }

    function removeFloatingClass(textfield) {
        requireModule("CSSCore").removeClass(textfield, classList.label_floating);
    }

    function shakeFloatingLabel(floatingLabel) {
        if (floatingLabel == null) return;
        requireModule("CSSCore").addClass(floatingLabel, classList.label_shake);
        setTimeout(function() {
            requireModule("CSSCore").removeClass(floatingLabel, classList.label_shake);
        }, 260);
    }
    
    function setLabelFloating(label, shouldFloat) {
        if (label == null) return;
        if (shouldFloat) {
            requireModule("CSSCore").addClass(label, classList.label_float_above);
        } else {
            requireModule("CSSCore").removeClass(label, classList.label_float_above);
        }
    }

    function setNotchedFocusedState(textfield, focused) {
        var notchedOutline = requireModule("Children").byClass(textfield, classList.notched_outline)[0];
        if (!notchedOutline) return;

        if (focused) {
            requireModule("CSSCore").addClass(notchedOutline, classList.notched_outline_notched);
        } else {
            requireModule("CSSCore").removeClass(notchedOutline, classList.notched_outline_notched);
        }
    }

    function setErrorState(textfieldInput, error) {
        var textfield = getTextfield(textfieldInput).textfield;

        error ? requireModule("CSSCore").addClass(textfield,    classList.error) :
                requireModule("CSSCore").removeClass(textfield, classList.error);
        textfieldInput.setAttribute("aria-invalid", error ? "true" : "false");
    }

    function setDisabledState(textfieldInput, disabled) {
        var textfield = getTextfield(textfieldInput).textfield;
        disabled ? requireModule("CSSCore").addClass(textfield,    classList.disabled)
                 : requireModule("CSSCore").removeClass(textfield, classList.disabled);
        disabled ? textfieldInput.disabled = true : textfieldInput.removeAttribute("disabled");
    }

    function setRequiredState(textfieldInput, required) {
        var textfield = getTextfield(textfieldInput);
        var floatingLabel = textfield.floatingLabel;

        required ? textfieldInput.required = true : textfieldInput.removeAttribute("required");
        
        if (floatingLabel !== null) {
            if (required) {
                requireModule("CSSCore").addClass(floatingLabel, classList.label_required);
            } else {
                requireModule("CSSCore").removeClass(floatingLabel, classList.label_required);
            }
        }
    }

    function setReadonlyState(textfieldInput, readonly) {
        var textfield = getTextfield(textfieldInput).textfield;
        readonly ? requireModule("CSSCore").addClass(textfield,    classList.readonly)
                 : requireModule("CSSCore").removeClass(textfield, classList.readonly);
        readonly ? textfieldInput.setAttribute("tabindex", "-1") : textfieldInput.removeAttribute("tabindex");
    }

    function setFilenameToInputFileLabel(textfieldFileInput, files, reset) {
        var textfield = getTextfield(textfieldFileInput).textfield;
        var fileLabelContainer = requireModule("Children").byClass(textfield, classList.input_file_label)[0];
        var fileSizeContainer  = requireModule("Children").byClass(textfield, classList.input_file_size)[0];
 
        if (!fileLabelContainer) return;
        if (!fileSizeContainer) return;

        if (reset) {
            fileLabelContainer.innerHTML = "";
            fileSizeContainer.innerHTML = "";
            return;
        }

        if (files && files.length == 1) {
            var filename = files[0].name;
            var filesize = requireModule("components:utils").formatFileSize(files[0].size);

            fileLabelContainer.innerText = filename;
            fileLabelContainer.title = filename;
            fileSizeContainer.innerText  = filesize;
            fileSizeContainer.title = filesize;
        }
    }

    function setHelperOptions(helperText, options) {
        if (!helperText) return;
        if (typeof options !== "object") return;

        if (Object.prototype.hasOwnProperty.call(options, "persistent")) {
            requireModule("CSSCore").addClass(helperText, classList.helper_text_persistent);
        }

        if (Object.prototype.hasOwnProperty.call(options, "validationMsg")) {
            requireModule("CSSCore").addClass(helperText, classList.helper_text_validation_msg);
        }
    }

    function getTextfield(element) {
        var textfield = requireModule("Parent").byClass(element, classList.container);
        var textfieldInput = element;
        var floatingLabel = requireModule("Children").byClass(textfield, classList.label)[0];

        return {
            textfield: textfield,
            input: textfieldInput,
            floatingLabel: floatingLabel,
            getVariant: function() {
                var variant = requireModule("CSSCore").hasClass(textfield, classList.filled) ? "filled" : "outlined";
                return variant;
            }
        }
    }

    function resetTextfield(textfieldInput) {
        var textfieldData = getTextfield(textfieldInput);
        var textfield = textfieldData.textfield;
        var floatingLabel = textfieldData.floatingLabel;

        var defaultValue = textfieldInput.defaultValue;

        setErrorState(textfieldInput, false);

        /*if (["date", "month", "week", "time", "datetime-local", "color", "number"].indexOf(textfieldInput.type) > -1) {
            setLabelFloating(floatingLabel, true);
            setNotchedFocusedState(textfield, true);
        } else {*/
            if (defaultValue.trim() == "") {
                setLabelFloating(floatingLabel, false);
                setNotchedFocusedState(textfield, false);
            } else {
                setLabelFloating(floatingLabel, true);
                setNotchedFocusedState(textfield, true);
            }

            if (textfieldInput.type == "file") {
                setFilenameToInputFileLabel(textfieldInput, null, true);
            }

            var filteredDialogPicker = inputTypesWithPicker.filter(function(i) {
                return i !== "file"
            });

            if (filteredDialogPicker.indexOf(textfieldInput.type) > -1) {
                textfieldInput.style.opacity = 0;
            }

        /*}*/
    }
 
    var eventHandler = {
        handleChangeEvent: function(target, event) {
            var textfield = getTextfield(target).textfield;
            if (target.type == "file") {
                var files = target.files;
                setFilenameToInputFileLabel(target, files);
                return;
            }
            var filteredDialogPicker = inputTypesWithPicker.filter(function(i) {
                return i !== "file"
            });

            if (filteredDialogPicker.indexOf(target.type) > -1) {
                target.style.opacity = 1;
            }
        },
        handleClickEvent: function(target, event) {
            if (hasDialogPicker(target.type)) {
                if ('showPicker' in HTMLInputElement.prototype) {
                    target.showPicker();
                }
            }
        },
        handleFocusEvent: function(target, event) {
            var eventType = event.type;
            setFocusedState(getTextfield(target).textfield, eventType == "focusin");

            var textfield = getTextfield(target).textfield;
            var input = getTextfield(target).input;
            var label = getTextfield(target).floatingLabel;
            var isEmpty = input.value.length === 0 || input.value.trim() == "";

            if (label) {
                if (eventType == "focusin") {
                    requireModule("CSSCore").addClass(label, classList.label_float_above);
                } else {
                    if (isEmpty && !hasDialogPicker(input.type)) {
                        requireModule("CSSCore").removeClass(label, classList.label_float_above);
                    }
                }
            } else if (eventType == "focusout") {
                if (!requireModule("CSSCore").hasClass(textfield, classList.no_label)) {
                    if (isEmpty) {
                        requireModule("CSSCore").removeClass(textfield, classList.label_floating);
                    }
                }
            }

            // Handling outlined textfield
            if (getTextfield(target).getVariant() === "outlined") {
                var notchedOutline = requireModule("Children").byClass(getTextfield(target).textfield, classList.notched_outline)[0];
                if (notchedOutline) {
                    if (eventType == "focusin") {
                        requireModule("CSSCore").addClass(notchedOutline, classList.notched_outline_notched);
                    } else {
                        if (isEmpty && !hasDialogPicker(input.type)) {
                            requireModule("CSSCore").removeClass(notchedOutline, classList.notched_outline_notched);
                        }
                    }
                }
            }

            // Handling filled textfield
            if (getTextfield(target).getVariant() === "filled") {
                // Activating line ripple
                var lineRipple = requireModule("Children").byClass(getTextfield(target).textfield, classList.line_ripple)[0];
                if (lineRipple) {
                    eventType == "focusin" ? requireModule("CSSCore").addClass(lineRipple, classList.line_ripple_active)
                        : requireModule("CSSCore").removeClass(lineRipple, classList.line_ripple_active);
                }
            }

            // Handling required textfield input
            if (input.hasAttribute("required")) {
                handleRequiredTextfieldInput(input, eventType);
            }

            // Handling pattern textfield input
            if (input.hasAttribute("pattern")) {
                handlePatternTextfieldInput(input, eventType);
            }
        },
        handleKeyboardEvent: function(target, event) {
            var eventType = event.type;
            var textfield = getTextfield(target).textfield;

            if (eventType == "keyup") {
                if (target.hasAttribute("pattern")) {
                    var pattern = new RegExp(target.getAttribute("pattern"));
                    // If pattern is not valid, add error class to textfield
                    if (!pattern.test(target.value)) {
                        requireModule("CSSCore").addClass(textfield, classList.error);
                    } else {
                        requireModule("CSSCore").removeClass(textfield, classList.error);
                    }
                }
            } else if (eventType == "keydown") {

            }
        },
        handleInputEvent: function(target, event) {
            var textfield = getTextfield(target);

            // handling character counter
            if (textfield.input.hasAttribute("maxlength")) {
                var characterCounter = requireModule("Children").byClass(textfield.textfield.parentNode, classList.character_counter)[0];
                if (characterCounter) {
                    var maxLength = parseInt(textfield.input.getAttribute("maxlength"), 10);
                    var currentLength = textfield.input.value.length;
                    characterCounter.textContent = currentLength + " / " + maxLength;
                }
            }
        },
        handleActionButton: function(actionButton) {
            var textfieldInput = requireModule("Child").byClass(actionButton.parentNode, classList.input);
            if (!textfieldInput) return;

            var textfield = getTextfield(textfieldInput);
            var action = actionButton.dataset.action;

            if (action) {
                if (action == "toggle_visibility") {
                    if (textfieldInput.type == "password") {
                        textfieldInput.type = "text";
                    } else if (textfieldInput.type == "text") {
                        textfieldInput.type = "password";
                    }
                }
                
                if (action == "clear") {
                    textfieldInput.value = "";
                    textfieldInput.focus();
                    var inputEvent = new Event("focusin", { bubbles: true });
                    textfieldInput.dispatchEvent(inputEvent);
                }
                
                if (action == "copy") {
                    requireModule("DOMUtils").copy(textfieldInput);
                }
            }
        }
    };

    var adapter = {
        setDisabled: function(disabled) {
            setDisabledState(this._root, disabled);
        },
        setReadonly: function(readonly) {
            setReadonlyState(this._root, readonly);
        },
        setRequired: function(required) {
            setRequiredState(this._root, required);
        },
        setHelperText: function(text, options) {
            var textfield = getTextfield(this._root).textfield;
            var helperText = requireModule("$").fromIDOrElement(this._root.id + SUPPORTING_TEXT_PREFIX);
            //
            if (text == "") {
                if (helperText !== null) {
                    var helperLine = helperText.parentNode;
                    helperLine.parentNode.removeChild(helperLine);
                }
                return;
            }
            
            if (helperText !== null) {
                helperText.textContent = text;
                setHelperOptions(helperText, options);
                return;
            }

            // Creating helper text element if it doesn't exists
            if (helperText == null) {
                // Creating helper line if doesn't exists
                var helperLine = document.createElement("div");
                requireModule("CSSCore").addClass(helperLine, classList.helper_line);

                helperText = document.createElement("p");
                helperText.setAttribute("id", this._root.id + SUPPORTING_TEXT_PREFIX);
                requireModule("CSSCore").addClass(helperText, classList.helper_text);
                helperText.setAttribute("aria-live", "polite");
                helperText.textContent = text;
                
                setHelperOptions(helperText, options);

                helperLine.appendChild(helperText);
                textfield.parentNode.insertBefore(helperLine, textfield.nextSibling);

            }
            helperText.textContent = text;
        },
        setError: function(error) {
            setErrorState(this._root, error);
        },
        setErrorText: function(text) {
            
        },
        getVariant: function() {
            var textfield = getTextfield(this._root).textfield;
            return textfield.getAttribute("data-variant") || "";
        },
        getValue: function() {
            return this.value;
        },
    };

    module.exports = {
        handleChangeEvent: eventHandler.handleChangeEvent,
        handleClickEvent: eventHandler.handleClickEvent,
        handleFocusEvent: eventHandler.handleFocusEvent,
        handleKeyboardEvent: eventHandler.handleKeyboardEvent,
        handleInputEvent: eventHandler.handleInputEvent,
        handleActionButton: eventHandler.handleActionButton,
        getTextfield: getTextfield,
        setDisabled: adapter.setDisabled,
        setRequired: adapter.setRequired,
        setReadonly: adapter.setReadonly,
        setHelperText: adapter.setHelperText,
        setError: adapter.setError,
        setErrorText: adapter.setErrorText,
        getVariant: adapter.getVariant,
        getValue: adapter.getValue,
        reset: resetTextfield
    };
}), null);

defineModule("Textfield", ["TextfieldAdapter"], (function(global, requireModule, requireDynamic, requireLazy, module, exports, adapter) {
    'use strict';

    function textfield(id) {
        this._root = requireModule("$").fromIDOrElement(id);
    }

    textfield.prototype.getVariant = function() {
        return adapter.getVariant.call(this);
    };

    textfield.prototype.setDisabled = function(disabled) {
        if (typeof disabled !== "boolean") throw new TypeError("argument 'disabled' must be a boolean type");
        adapter.setDisabled.call(this, disabled);
    };
    
    textfield.prototype.setReadonly = function(readonly) {
        if (typeof readonly !== "boolean") throw new TypeError("argument 'readonly' must be a boolean type");
        adapter.setReadonly.call(this, readonly);
    };
    
    textfield.prototype.setRequired = function(required) {
        if (typeof required !== "boolean") throw new TypeError("argument 'required' must be a boolean type");
        adapter.setRequired.call(this, required);
    };

    textfield.prototype.setError = function(error) {
        if (typeof error !== "boolean") throw new TypeError("argument 'error' must be a boolean type");
        adapter.setError.call(this, error);
    };

    textfield.prototype.setHelperText = function(text, options) {
        if (typeof text !== "string") throw new TypeError("argument 'text' must be a string type");
        adapter.setHelperText.call(this, text, options);
    };

    textfield.prototype.getValue = function() {
        return adapter.getValue.call(this._root);
    };
    
    textfield.prototype.setValue = function(value) {
    if (typeof value !== "string") throw new TypeError("argument 'value' must be a string type");
        return adapter.setValue.call(this._root);
    };

    module.exports = textfield;
}), null);

defineModule("SelectAdapter", ["Child", "Children", "CSSCore", "Event", "KeyboardEvent", "Parent"], (function(global, requireModule, requireDynamic, requireLazy, module, exports) {
    'use strict';

    var classList = requireModule("components:classlist").select;
    var menuClassList = requireModule("components:classlist").menu;
    var listClassList = requireModule("components:classlist").list;

    var state = requireModule("components:state");
    var isAnimating = false;

    function getMenuPosition(menu, trigger) {
        var isExposed = true;

        requireModule("CSSCore").addClass(menu, 'c_menu-surface--measuring');
        var menuRect = menu.getBoundingClientRect();
        requireModule("CSSCore").removeClass(menu, 'c_menu-surface--measuring');

        var triggerRect = trigger.getBoundingClientRect(); // Use trigger's rect for viewport checks
        var viewportHeight = window.innerHeight;
        var Y_PADDING = 16;
        var styles = {};

        // --- 1. Horizontal Position & Width ---
        if (isExposed) {
            // Position relative to the trigger's offset within the anchor
            styles.left = trigger.offsetLeft + 'px';
            styles.width = trigger.offsetWidth + 'px';
        } else {
            styles.left = '0px';
        }

        // --- 2. Vertical Position (relative to the trigger) ---
        var spaceBelow = viewportHeight - triggerRect.bottom;
        var spaceAbove = triggerRect.top;
        var openDownwards = (spaceBelow >= menuRect.height || spaceBelow >= spaceAbove);

        if (openDownwards) {
            styles.transformOrigin = 'top left';
            styles.maxHeight = Math.max(0, spaceBelow - Y_PADDING) + 'px';
            styles.bottom = 'auto';
            // For exposed, top starts at the trigger's bottom edge.
            styles.top = isExposed ? trigger.offsetHeight + 'px' : '0px';
        } else { // Open upwards
            styles.transformOrigin = 'bottom left';
            styles.maxHeight = Math.max(0, spaceAbove - Y_PADDING) + 'px';
            styles.top = 'auto';
            // For exposed, bottom is positioned relative to the trigger's top edge.
            styles.bottom = isExposed  ? trigger.offsetHeight + 'px' : '0px';
        }

        return styles;
    }
    
    function open(menu, trigger, callback) {
        var selectData = getSelect(menu);

        if (isAnimating) {
            return;
        }

        var selectElement = requireModule("Parent").byClass(menu, classList.container);
        var menuEl = menu;
        var triggerEl = trigger; // Can be null for programmatic open

        // Handle toggle behavior for exposed dropdowns
        if (state.ACTIVE_MENU === menuEl) {
            //close();
            return;
        }

        // If another menu is open, close it first.
        if (state.ACTIVE_MENU && state.ACTIVE_MENU !== menuEl) {
            //close();
        }

        isAnimating = true;
        state.ACTIVE_MENU = menuEl;
        state.ACTIVE_MENU_TRIGGER = triggerEl; // Store the trigger

        requireModule("CSSCore").addClass(selectElement, classList.activated);
        selectData.selectedText.setAttribute("aria-expanded", "true");

        // 1. Calculate and apply position
        var styles = getMenuPosition(menuEl, trigger);
        for (var key in styles) {
            menuEl.style[key] = styles[key];
        }

        // 2. Start the opening animation
        requireModule("CSSCore").addClass(menuEl, menuClassList.opening);

        // 3. Use requestAnimationFrame to apply the final state in the next frame
        requestAnimationFrame(function() {
            //setFocusToMenu(menuEl);
            if (typeof callback == "function") {
                callback(menu);
            }

            requireModule("CSSCore").addClass(menuEl, menuClassList.open);

            // 4. After the animation duration (120ms), remove the opening class.
            setTimeout(function() {
                requireModule("CSSCore").removeClass(menuEl, menuClassList.opening);
                isAnimating = false;
            }, 120);
        });
    }

    function setLabelFloating(label, shouldFloat) {
        if (label == null) return;
        if (shouldFloat) {
            requireModule("CSSCore").addClass(label, classList.label_float_above);
        } else {
            requireModule("CSSCore").removeClass(label, classList.label_float_above);
        }
    }

    function setFocusedState(selectElement, focused) {
        var selectedText = requireModule("Children").byClass(selectElement, classList.selected_text);
        if (focused) {
            requireModule("CSSCore").addClass(selectElement, classList.focused);
        } else {
            requireModule("CSSCore").removeClass(selectElement, classList.focused);
        }
    }
    
    function setNotchedFocusedState(selectElement, focused) {
        var notchedOutline = requireModule("Children").byClass(selectElement, classList.notched_outline)[0];
        if (!notchedOutline) return;

        if (focused) {
            requireModule("CSSCore").addClass(notchedOutline, classList.notched_outline_notched);
        } else {
            requireModule("CSSCore").removeClass(notchedOutline, classList.notched_outline_notched);
        }
    }

    function setValidState(selectInput, error) {
        console.log(selectInput, error);
        var selectElement = getSelect(selectInput).parent;

        error ? requireModule("CSSCore").addClass(selectElement,    classList.invalid) :
                requireModule("CSSCore").removeClass(selectElement, classList.invalid);
        selectInput.setAttribute("aria-invalid", error ? "true" : "false");
    }

    function setDisabledState(selectInput, disabled) {
        var selectElement = getSelect(selectInput).parent;
        disabled ? requireModule("CSSCore").addClass(selectElement,    classList.disabled)
                 : requireModule("CSSCore").removeClass(selectElement, classList.disabled);
        disabled ? selectInput.disabled = true : selectInput.removeAttribute("disabled");
    }

    function setRequiredState(selectInput, required) {
        console.log(selectInput, required);
        var selectData = getSelect(selectInput);
        var floatingLabel = selectData.floatingLabel;

        required ? selectInput.required = true : selectInput.removeAttribute("required");
        
        if (floatingLabel !== null) {
            if (required) {
                requireModule("CSSCore").addClass(floatingLabel, classList.label_required);
            } else {
                requireModule("CSSCore").removeClass(floatingLabel, classList.label_required);
            }
        }
    }

    function setReadonlyState(selectInput, readonly) {
        var selectElement = getSelect(selectInput).parent;
        readonly ? requireModule("CSSCore").addClass(selectElement,    classList.readonly)
                 : requireModule("CSSCore").removeClass(selectElement, classList.readonly);
        readonly ? selectInput.setAttribute("tabindex", "-1") : selectInput.removeAttribute("tabindex");
    }
    
    function setFocusToMenu(menu) {
        var selectedMenuItem = requireModule("Children").byClass(menu, listClassList.activated)[0];
        if (selectedMenuItem) {
            selectedMenuItem.focus();
        } else {
            var menuItems = requireModule("Children").byClass(menu, listClassList.item);
            menuItems[0].focus();
        }
    }
    
    function getSelect(element) {
        var parent = requireModule("Parent").byClass(element, classList.container)
        var selectedText = requireModule("Children").byClass(parent, classList.selected_text)[0];
        var selectInput = requireModule("Children").byClass(parent, classList.input)[0];
        var floatingLabel = requireModule("Children").byClass(parent, classList.label)[0];
        var lineRipple = requireModule("Children").byClass(parent, classList.line_ripple)[0];
        var menu = requireModule("Children").byClass(parent, menuClassList.container)[0];

        return {
            parent: parent,
            input: selectInput,
            selectedText: selectedText,
            floatingLabel: floatingLabel,
            lineRipple: lineRipple,
            getVariant: function() {
                return parent.dataset.variant;
            },
            menu: menu
        }
    }

    function getMenuInstance() {
        var selectData = getSelect(this._root);
        var menu = selectData.menu;
        return new(requireModule("Menu"))(menu);
    }
    
    function setItemSelection(item, event) {
        var selectData = getSelect(item);
        var selectElement = selectData.parent;
        var selectInput = selectData.input;
        var selectLabel = selectData.floatingLabel;
        var selectedText = selectData.selectedText;
        var lineRipple = selectData.lineRipple;
        var variant = selectData.getVariant();

        if (!selectedText) return;

        // Does the component has a error state?
        if (requireModule("CSSCore").hasClass(selectElement, classList.invalid)) {
            requireModule("CSSCore").removeClass(selectElement, classList.invalid);
            selectInput.setAttribute("aria-invalid", "false");
        }

        var value = item.dataset.value.trim();

        if (value == "") {
            setLabelFloating(selectLabel, false);
            setFocusedState(selectElement, false);
            setNotchedFocusedState(selectElement, false);

            selectedText.innerText = "";
        } else {
            setLabelFloating(selectLabel, true);
            setFocusedState(selectElement, true);
            setNotchedFocusedState(selectElement, true);

            selectedText.innerText = item.innerText;
            selectedText.focus();
            selectInput.value = value;
        }

        requireModule("components:state").LAST_FOCUSED_ELEMENT = selectElement;
        requireModule("MenuAdapter").close();
    }

    function resetSelect(selectInput) {
        var selectData = getSelect(selectInput);
        var selectElement = selectData.parent;
        var selectInput = selectData.input;
        var selectLabel = selectData.floatingLabel;
        var selectedText = selectData.selectedText;
        var lineRipple = selectData.lineRipple;
        var menu = selectData.menu;

        selectInput.value = "";
        selectedText.innerText = "";

        setLabelFloating(selectLabel, false);
        setFocusedState(selectElement, false);
        setNotchedFocusedState(selectElement, false);

        requireModule("MenuAdapter").reset(menu);
    }

    var eventHandler = {
        handleKeyboardEvent: function(target, event, isFromListItem) {
            if (isFromListItem) {
                setItemSelection(target, event);
            }
            console.log(event);
            // TODO implement open select menu from keyboard
            if (event) {
                event.preventDefault(); 
            }
        },
        handleClickEventFromMenu: function(target, event) {
            setItemSelection(target, event);
        },
        handleClickEvent: function(target, event) {
            var selectData = getSelect(target);
            var selectElement = selectData.parent;

            var selectMenu = requireModule("Child").byClass(selectElement, menuClassList.container);

            open(selectMenu, target);
        },
        handleFocusEvent: function(target, event) {
            var eventType = event.type;
            var selectElements = getSelect(target);
            var selectElement = selectElements.parent;
            var selectLabel = selectElements.floatingLabel;
            var selectedText = selectElements.selectedText;
            var lineRipple = selectElements.lineRipple;
            var variant = selectElements.getVariant();
            var isEmpty = selectedText.innerText == "";
            var relatedTarget = event.relatedTarget;
            
            if (!selectLabel) return;

            if (eventType == "focusin") {
                setFocusedState(selectElement, true);
                setNotchedFocusedState(selectElement, true);
                setLabelFloating(selectLabel, true);
                requireModule("Event").addEventListener(selectedText, 'keydown', eventHandler.handleKeyboardEvent);
            } else if (eventType == "focusout") {
                console.log(relatedTarget);
                if (relatedTarget !== null) {
                    console.log(relatedTarget);
                } else {
                    if (isEmpty) {
                        setLabelFloating(selectLabel, false);
                        setNotchedFocusedState(selectElement, false);
                        setFocusedState(selectElement, false);
                    } else {
                        setFocusedState(selectElement, false);
                    }
                }

                requireModule("Event").removeEventListener(selectedText, 'keydown', eventHandler.handleKeyboardEvent);
            }

            if (variant == "filled") {
                // Activating line ripple
                var lineRipple = requireModule("Children").byClass(selectElement, classList.line_ripple)[0];
                if (lineRipple) {
                    eventType == "focusin" ? requireModule("CSSCore").addClass(lineRipple, classList.line_ripple_active)
                        : requireModule("CSSCore").removeClass(lineRipple, classList.line_ripple_active);
                }
            }
        },
    };

    var adapter = {
        addClass: function(className) {
            var selectData = getSelect(this._root);
            requireModule("CSSCore").addClass(selectData.parent, className);
        },
        removeClass: function(className) {
            var selectData = getSelect(this._root);
            requireModule("CSSCore").removeClass(selectData.parent, className);
        },
        hasClass: function(className) {
            var selectData = getSelect(this._root);
            return requireModule("CSSCore").hasClass(selectData.parent, className);
        },
        setSelectedText: function(text) {
            var selectData = getSelect(this._root);
            setLabelFloating(selectData.floatingLabel, true);
            setNotchedFocusedState(selectData.parent, true);
            setFocusedState(selectData.parent, true);
            selectData.selectedText.innerText = text;
        },
        setValue: function(value) {
            var selectData = getSelect(this._root);
            selectData.input.value = value;
        },
        getValue: function() {
            var selectData = getSelect(this._root);
            return selectData.input.value;
        },
        setSelectedIndex: function(index) {
            this.menu.setSelectedIndex(index);
        },
        getSelectedIndex: function() {
            return this.menu.getSelectedIndex();
        },
        getMenuItemValues: function() {
            return this.menu.getItems().map(function(i) {
                return i.dataset.value
            });
        },
        getMenuItemCount: function() {
            return this.menu.getItemCount();
        },
        setValid: function(isValid) {
            console.log(this, isValid);
            setValidState(this._root, isValid);
        },
        isValid: function() {
            var selectData = getSelect(this._root);
            return !requireModule("CSSCore").hasClass(selectData.parent, classList.invalid);
        },
        setDisabled: function(disabled) {
            setDisabled(this._root, disabled);
        },
        setReadonly: function(readonly) {
            setReadonlyState(this._root, readonly);
        },
        setRequired: function(required) {
            console.log(this, required);
            setRequiredState(this._root, required);
        }
    };

    module.exports = {
        getMenuInstance: getMenuInstance,
        handleKeyboardEvent: eventHandler.handleKeyboardEvent,
        handleClickEventFromMenu: eventHandler.handleClickEventFromMenu,
        handleFocusEvent: eventHandler.handleFocusEvent,
        handleClickEvent: eventHandler.handleClickEvent,
        addClass: adapter.addClass,
        removeClass: adapter.removeClass,
        hasClass: adapter.hasClass,
        setSelectedText: adapter.setSelectedText,
        openMenu: adapter.openMenu,
        closeMenu: adapter.closeMenu,
        setValue: adapter.setValue,
        getValue: adapter.getValue,
        setSelectedIndex: adapter.setSelectedIndex,
        getSelectedIndex: adapter.getSelectedIndex,
        getMenuItemValues: adapter.getMenuItemValues,
        getMenuItemCount: adapter.getMenuItemCount,
        setValid: adapter.setValid,
        isValid: adapter.isValid,
        setDisabled: adapter.setDisabled,
        setReadonly: adapter.setReadonly,
        setRequired: adapter.setRequired,
        setHelperTextContent: adapter.setHelperTextContent,
        reset: resetSelect
    };
}), null);

defineModule("Select", ["SelectAdapter"], (function(global, requireModule, requireDynamic, requireLazy, module, exports, adapter) {
    'use strict';
    
    function select(id) {
        this._root = requireModule("$").fromIDOrElement(id);
        this.menu = adapter.getMenuInstance.call(this);
    }
    select.prototype.addClass = function(className) {
        if (typeof className !== "string") throw new TypeError("argument 'className' must be a string type");
        adapter.addClass.call(this, className);
    };
    select.prototype.removeClass = function(className) {
        if (typeof className !== "string") throw new TypeError("argument 'className' must be a string type");
        adapter.removeClass.call(this, className);
    };
    select.prototype.hasClass = function(className) {
        if (typeof className !== "string") throw new TypeError("argument 'className' must be a string type");
        adapter.hasClass.call(this, className);
    };
    select.prototype.setSelectedText = function(text) {
        if (typeof text !== "string") throw new TypeError("argument 'text' must be a string type");
        adapter.setSelectedText.call(this, text);
    };
    select.prototype.openMenu = function() {
        adapter.openMenu.call(this);
    };
    select.prototype.closeMenu = function() {
        adapter.closeMenu.call(this);
    };
    select.prototype.getValue = function() {
        return adapter.getValue.call(this);
    };
    select.prototype.setValue = function(value) {
        if (typeof value !== "string") throw new TypeError("argument 'value' must be a string type");
        adapter.setValue.call(this, value);
    };
    select.prototype.setSelectedIndex = function(index) {
        adapter.setSelectedIndex.call(this, index);
    };
    select.prototype.getSelectedIndex = function() {
        return adapter.getSelectedIndex.call(this);
    };
    select.prototype.getMenuItemCount = function() {
        return adapter.getMenuItemCount.call(this);
    };
    select.prototype.getMenuItemValues = function() {
        return adapter.getMenuItemValues.call(this);
    };
    select.prototype.setValid = function(isValid) {
        adapter.setValid.call(this, isValid);
    };
    select.prototype.isValid = function() {
        return adapter.isValid.call(this);
    };
    select.prototype.setDisabled = function(disabled) {
        adapter.setDisabled.call(this, disabled);
    };
    select.prototype.setReadonly = function(readonly) {
        adapter.setReadonly.call(this, readonly);
    };
    select.prototype.setRequired = function(isRequired) {
        adapter.setRequired.call(this, isRequired);
    };
    select.prototype.setHelperTextContent = function(content) {
        adapter.setHelperTextContent.call(this, content);
    };

    module.exports = select;
}), null);

defineModule("TopAppBarAdapter", ["EventType"], (function(global, requireModule, requireDynamic, requireLazy, module, exports, eventTypes) {
    'use strict';

    var classList = requireModule("components:classlist").top_app_bar;
    
    var eventHandler = {
        handleNavigationClick: function(navigationIcon, event) {
            var topAppBar = getTopAppBar(navigationIcon);
            if (!topAppBar) return;
            
            // Trigger custom event for navigation click
            var customEvent = new CustomEvent('topappbar:navigation', {
                bubbles: true,
                detail: { topAppBar: topAppBar }
            });
            topAppBar.dispatchEvent(customEvent);
        },
        
        handleActionClick: function(actionItem, event) {
            var topAppBar = getTopAppBar(actionItem);
            if (!topAppBar) return;
            
            // Trigger custom event for action click
            var customEvent = new CustomEvent('topappbar:action', {
                bubbles: true,
                detail: { 
                    topAppBar: topAppBar,
                    action: actionItem
                }
            });
            topAppBar.dispatchEvent(customEvent);
        },
        
        handleSearchInput: function(searchInput, event) {
            var topAppBar = getTopAppBar(searchInput);
            if (!topAppBar) return;
            
            var clearButton = topAppBar.querySelector('[data-search-clear]');

            var value = searchInput.value.trim();
            
            // Show/hide clear button
            if (clearButton) {
                if (value.length > 0) {
                    clearButton.style.display = 'inline-flex';
                } else {
                    clearButton.style.display = 'none';
                }
            }
            
            // Add active class
            if (value.length > 0) {
                requireModule("CSSCore").addClass(topAppBar, classList.search_active);
            } else {
                requireModule("CSSCore").removeClass(topAppBar, classList.search_active);
            }
            
            // Trigger custom event
            var customEvent = new CustomEvent('topappbar:search', {
                bubbles: true,
                detail: { 
                    topAppBar: topAppBar,
                    query: value
                }
            });
            topAppBar.dispatchEvent(customEvent);
        },
        
        handleSearchClear: function(clearButton, event) {
            var topAppBar = getTopAppBar(clearButton);
            if (!topAppBar) return;
            
            var searchInput = topAppBar.querySelector('[data-search-input]');
            if (searchInput) {
                searchInput.value = '';
                searchInput.focus();
                clearButton.style.display = 'none';
                requireModule("CSSCore").removeClass(topAppBar, classList.search_active);
                
                // Trigger custom event
                var customEvent = new CustomEvent('topappbar:search:clear', {
                    bubbles: true,
                    detail: { topAppBar: topAppBar }
                });
                topAppBar.dispatchEvent(customEvent);
            }
        },
        
        handleSearchClose: function(closeButton, event) {
            var topAppBar = getTopAppBar(closeButton);
            if (!topAppBar) return;
            
            var searchInput = topAppBar.querySelector('[data-search-input]');
            if (searchInput) {
                searchInput.value = '';
                searchInput.blur();
            }
            
            var clearButton = topAppBar.querySelector('[data-search-clear]');
            if (clearButton) {
                clearButton.style.display = 'none';
            }
            
            requireModule("CSSCore").removeClass(topAppBar, classList.search_active);
            
            // Collapse search on mobile
            if (topAppBar.hasAttribute('data-search-collapsible') && window.innerWidth < 600) {
                requireModule("CSSCore").removeClass(topAppBar, classList.search_expanded);
                requireModule("CSSCore").addClass(topAppBar, classList.search_collapsed);
            }
            
            // Reset search container width
            var searchContainer = topAppBar.querySelector('[data-search-bar]');
            if (searchContainer) {
                searchContainer.style.maxWidth = '';
            }
            
            // Trigger custom event
            var customEvent = new CustomEvent('topappbar:search:close', {
                bubbles: true,
                detail: { topAppBar: topAppBar }
            });
            topAppBar.dispatchEvent(customEvent);
        },
        
        handleSearchFocus: function(searchInput, event) {
            var topAppBar = getTopAppBar(searchInput);
            if (!topAppBar) return;

            var searchIcon = topAppBar.querySelector('.' + classList.search_icon);
            if (searchIcon) {
                searchIcon.style.display = "none";
            }
            
            requireModule("CSSCore").addClass(topAppBar, classList.search_active);
            
            // On mobile with collapsible search, ensure expanded state
            if (topAppBar.hasAttribute('data-search-collapsible') && window.innerWidth < 600) {
                requireModule("CSSCore").removeClass(topAppBar, classList.search_collapsed);
                requireModule("CSSCore").addClass(topAppBar, classList.search_expanded);
            }
        },
        
        handleSearchBlur: function(searchInput, event) {
            var topAppBar = getTopAppBar(searchInput);
            if (!topAppBar) return;
            
            // Only remove active class if input is empty
            if (searchInput.value.trim().length === 0) {
                requireModule("CSSCore").removeClass(topAppBar, classList.search_active);
            }
        },
        
        handleScroll: function(topAppBar, scrollTop, scrollHeight, clientHeight) {
            var scrollBehavior = getScrollBehavior(topAppBar);
            
            if (!scrollBehavior) return;
            
            var threshold = scrollBehavior.threshold || 0;
            
            switch (scrollBehavior.type) {
                case 'fixed':
                    handleFixedScroll(topAppBar, scrollTop, threshold);
                    break;
                case 'short':
                    handleShortScroll(topAppBar, scrollTop, threshold);
                    break;
                case 'prominent':
                    handleProminentScroll(topAppBar, scrollTop, scrollHeight, clientHeight, threshold);
                    break;
            }
        },

        handleSearchTrigger: function(triggerButton, event) {
            var topAppBar = getTopAppBar(triggerButton);
            if (!topAppBar) return;
            
            // Expand search
            requireModule("CSSCore").removeClass(topAppBar, classList.search_collapsed);
            requireModule("CSSCore").addClass(topAppBar, classList.search_expanded);
            
            // Focus search input
            var searchInput = topAppBar.querySelector('[data-search-input]');
            if (searchInput) {
                setTimeout(function() {
                    searchInput.focus();
                }, 100); // Small delay for animation
            }
            
            // Trigger custom event
            var customEvent = new CustomEvent('topappbar:search:open', {
                bubbles: true,
                detail: { topAppBar: topAppBar }
            });
            topAppBar.dispatchEvent(customEvent);
        },
        setTitle: function(title) {
            var headlineElement = this._root.querySelector('.' + classList.headline);
            var titleElement = this._root.querySelector('.' + classList.title);
            
            if (headlineElement) {
                headlineElement.textContent = title;
            } else if (titleElement) {
                titleElement.textContent = title;
            }
        },
        
        getTitle: function() {
            var headlineElement = this._root.querySelector('.' + classList.headline);
            var titleElement = this._root.querySelector('.' + classList.title);
            
            if (headlineElement) {
                return headlineElement.textContent;
            } else if (titleElement) {
                return titleElement.textContent;
            }
            return '';
        },
        
        setSubtitle: function(subtitle) {
            var subtitleElement = this._root.querySelector('.' + classList.subtitle);
            if (subtitleElement) {
                subtitleElement.textContent = subtitle;
                subtitleElement.style.display = subtitle ? 'block' : 'none';
            }
        },
        
        getSubtitle: function() {
            var subtitleElement = this._root.querySelector('.' + classList.subtitle);
            return subtitleElement ? subtitleElement.textContent : '';
        },
        
        setLogo: function(logoSrc) {
            var logoElement = this._root.querySelector('.' + classList.logo);
            if (logoElement) {
                logoElement.src = logoSrc;
            }
        }
    };
    
    /**
     * Get the top app bar element from a child element
     */
    function getTopAppBar(element) {
        return requireModule("Parent").byAttribute(element, 'data-top-app-bar');
    }
    
    /**
     * Get scroll behavior configuration
     */
    function getScrollBehavior(topAppBar) {
        var behaviorData = topAppBar.getAttribute('data-scroll-behavior');
        if (!behaviorData) return null;
        
        try {
            return JSON.parse(behaviorData);
        } catch (e) {
            return null;
        }
    }
    
    /**
     * Handle fixed scroll behavior
     */
    function handleFixedScroll(topAppBar, scrollTop, threshold) {
        if (scrollTop > threshold) {
            requireModule("CSSCore").addClass(topAppBar, classList.scrolled);
        } else {
            requireModule("CSSCore").removeClass(topAppBar, classList.scrolled);
        }
    }
    
    /**
     * Handle short scroll behavior
     */
    function handleShortScroll(topAppBar, scrollTop, threshold) {
        if (scrollTop > threshold) {
            requireModule("CSSCore").addClass(topAppBar, classList.short_collapsed);
        } else {
            requireModule("CSSCore").removeClass(topAppBar, classList.short_collapsed);
        }
    }
    
    /**
     * Handle prominent scroll behavior
     */
    function handleProminentScroll(topAppBar, scrollTop, scrollHeight, clientHeight, threshold) {
        var progress = Math.min(scrollTop / (scrollHeight - clientHeight), 1);
        
        if (scrollTop > threshold) {
            requireModule("CSSCore").addClass(topAppBar, classList.scrolled);
        } else {
            requireModule("CSSCore").removeClass(topAppBar, classList.scrolled);
        }
        
        var customEvent = new CustomEvent('topappbar:scroll', {
            bubbles: true,
            detail: { 
                topAppBar: topAppBar,
                scrollTop: scrollTop,
                progress: progress
            }
        });
        topAppBar.dispatchEvent(customEvent);
    }
    
    /**
     * Initialize scroll listener for a top app bar
     */
    function initScrollListener(topAppBar) {
        var scrollBehavior = getScrollBehavior(topAppBar);
        if (!scrollBehavior) return;
        
        var scrollTarget = scrollBehavior.target || window;
        var scrollElement = scrollBehavior.target ? document.querySelector(scrollBehavior.target) : window;
        
        if (!scrollElement) return;
        
        var handleScroll = function() {
            var scrollTop = scrollElement === window ? window.pageYOffset : scrollElement.scrollTop;
            var scrollHeight = scrollElement === window ? document.documentElement.scrollHeight : scrollElement.scrollHeight;
            var clientHeight = scrollElement === window ? window.innerHeight : scrollElement.clientHeight;
            
            eventHandler.handleScroll(topAppBar, scrollTop, scrollHeight, clientHeight);
        };
        
        requireModule("Event").addEventListener(scrollElement, 'scroll', handleScroll, { passive: true });
        
        handleScroll();
    }
    
    /**
     * Initialize search functionality for a top app bar
     */
    function initSearchListeners(topAppBar) {
        if (!topAppBar.hasAttribute('data-search-mode')) return;
        
        var searchInput = topAppBar.querySelector('[data-search-input]');
        var searchClear = topAppBar.querySelector('[data-search-clear]');
        var searchClose = topAppBar.querySelector('[data-search-close]');
        var searchTrigger = topAppBar.querySelector('[data-search-trigger]');
        
        if (searchInput) {
            requireModule("Event").addEventListener(searchInput, 'input', function(event) {
                eventHandler.handleSearchInput(searchInput, event);
            });
            
            requireModule("Event").addEventListener(searchInput, 'focus', function(event) {
                eventHandler.handleSearchFocus(searchInput, event);
            });
            
            requireModule("Event").addEventListener(searchInput, 'blur', function(event) {
                eventHandler.handleSearchBlur(searchInput, event);
            });
        }
        
        if (searchClear) {
            requireModule("Event").addEventListener(searchClear, eventTypes.MOUSE.CLICK, function(event) {
                eventHandler.handleSearchClear(searchClear, event);
            });
        }
        
        if (searchClose) {
            requireModule("Event").addEventListener(searchClose, eventTypes.MOUSE.CLICK, function(event) {
                eventHandler.handleSearchClose(searchClose, event);
            });
        }
        
        if (searchTrigger) {
            requireModule("Event").addEventListener(searchTrigger, eventTypes.MOUSE.CLICK, function(event) {
                eventHandler.handleSearchTrigger(searchTrigger, event);
            });
        }
    }

    /**
     * Handle window resize for adaptive search
     */
    function handleSearchResize() {
        var searchBars = document.querySelectorAll('[data-search-mode]');
        searchBars.forEach(function(topAppBar) {
            var searchWidth = topAppBar.getAttribute('data-search-width');
            var searchContainer = topAppBar.querySelector('[data-search-bar]');
            
            if (!searchContainer || searchWidth === 'full') return;
            
            // Reset to default adaptive behavior
            if (window.innerWidth >= 600) {
                searchContainer.style.maxWidth = '';
            }
        });
    }

    var adapter = {
        setTitle: function(title) {
            var titleElement = this._root.querySelector('.' + classList.title);
            if (titleElement) {
                titleElement.textContent = title;
            }
        },
        
        getTitle: function() {
            var titleElement = this._root.querySelector('.' + classList.title);
            return titleElement ? titleElement.textContent : '';
        },
        
        getSearchQuery: function() {
            var searchInput = this._root.querySelector('[data-search-input]');
            return searchInput ? searchInput.value : '';
        },
        
        setSearchQuery: function(query) {
            var searchInput = this._root.querySelector('[data-search-input]');
            if (searchInput) {
                searchInput.value = query;
                eventHandler.handleSearchInput(searchInput, { target: searchInput });
            }
        },
        
        clearSearch: function() {
            var searchClear = this._root.querySelector('[data-search-clear]');
            if (searchClear) {
                eventHandler.handleSearchClear(searchClear, {});
            }
        },
        
        focusSearch: function() {
            var searchInput = this._root.querySelector('[data-search-input]');
            if (searchInput) {
                searchInput.focus();
            }
        },
        
        onNavigationClick: function(callback) {
            if (typeof callback !== "function") {
                throw new TypeError("argument 'callback' must be a function");
            }

            var navigationIcon = this._root.querySelector('[data-navigation-icon]');
            if (navigationIcon) {
                requireModule("Event").addEventListener(navigationIcon, eventTypes.MOUSE.CLICK, callback);
            }
        },
        
        onActionClick: function(callback) {
            if (typeof callback !== "function") {
                throw new TypeError("argument 'callback' must be a function");
            }
            
            var actionItems = this._root.querySelectorAll('[data-action-item]');
            actionItems.forEach(function(actionItem) {
                requireModule("Event").addEventListener(actionItem, eventTypes.MOUSE.CLICK, function(event) {
                    callback(event, actionItem);
                });
            });
        },
        
        onSearch: function(callback) {
            if (typeof callback !== "function") {
                throw new TypeError("argument 'callback' must be a function");
            }
            
            requireModule("Event").addEventListener(this._root, 'topappbar:search', callback);
        },
        
        onSearchClear: function(callback) {
            if (typeof callback !== "function") {
                throw new TypeError("argument 'callback' must be a function");
            }
            
            requireModule("Event").addEventListener(this._root, 'topappbar:search:clear', callback);
        },
        
        onSearchClose: function(callback) {
            if (typeof callback !== "function") {
                throw new TypeError("argument 'callback' must be a function");
            }
            
            requireModule("Event").addEventListener(this._root, 'topappbar:search:close', callback);
        }
    };
    
    module.exports = {
        handleNavigationClick: eventHandler.handleNavigationClick,
        handleActionClick: eventHandler.handleActionClick,
        handleSearchInput: eventHandler.handleSearchInput,
        handleSearchClear: eventHandler.handleSearchClear,
        handleSearchClose: eventHandler.handleSearchClose,
        handleSearchFocus: eventHandler.handleSearchFocus,
        handleSearchBlur: eventHandler.handleSearchBlur,
        handleScroll: eventHandler.handleScroll,
        handleSearchTrigger: eventHandler.handleSearchTrigger,
        initScrollListener: initScrollListener,
        initSearchListeners: initSearchListeners,
        setTitle: adapter.setTitle,
        getTitle: adapter.getTitle,
        getSearchQuery: adapter.getSearchQuery,
        setSearchQuery: adapter.setSearchQuery,
        clearSearch: adapter.clearSearch,
        focusSearch: adapter.focusSearch,
        onNavigationClick: adapter.onNavigationClick,
        onActionClick: adapter.onActionClick,
        onSearch: adapter.onSearch,
        onSearchClear: adapter.onSearchClear,
        onSearchClose: adapter.onSearchClose,
        handleSearchResize: handleSearchResize,

    };
}), null);

defineModule("TopAppBar", ["TopAppBarAdapter"], function(global, requireModule, requireDynamic, requireLazy, module, exports, adapter) {
    'use strict';

    /**
     * Top App Bar Component
     * @param {string} id - The ID or element of the top app bar
     */
    function TopAppBar(id) {
        console.log(id);
        this._root = requireModule("$").fromIDOrElement(id);
        
        // Initialize scroll listener if scroll behavior is defined
        requireModule("TopAppBarAdapter").initScrollListener(this._root);
        
        // Initialize search listeners if search mode is enabled
        requireModule("TopAppBarAdapter").initSearchListeners(this._root);
    }
    
    /**
     * Set the title of the top app bar
     * @param {string} title
     */
    TopAppBar.prototype.setTitle = function(title) {
        if (typeof title !== "string") {
            throw new TypeError("argument 'title' must be a string");
        }
        adapter.setTitle.call(this, title);
    };
    
    /**
     * Get the title of the top app bar
     * @returns {string}
     */
    TopAppBar.prototype.getTitle = function() {
        return adapter.getTitle.call(this);
    };

    /**
     * Set the subtitle of the top app bar
     * @param {string} subtitle
     */
    TopAppBar.prototype.setSubtitle = function(subtitle) {
        if (typeof subtitle !== "string") {
            throw new TypeError("argument 'subtitle' must be a string");
        }
        adapter.setSubtitle.call(this, subtitle);
    };

    /**
     * Get the subtitle of the top app bar
     * @returns {string}
     */
    TopAppBar.prototype.getSubtitle = function() {
        return adapter.getSubtitle.call(this);
    };

    /**
     * Set the logo source
     * @param {string} logoSrc
     */
    TopAppBar.prototype.setLogo = function(logoSrc) {
        if (typeof logoSrc !== "string") {
            throw new TypeError("argument 'logoSrc' must be a string");
        }
        adapter.setLogo.call(this, logoSrc);
    };

    TopAppBar.prototype.getSearchQuery = function() {
        return adapter.getSearchQuery.call(this);
    };
    
    /**
     * Set the search query
     * @param {string} query
     */
    TopAppBar.prototype.setSearchQuery = function(query) {
        if (typeof query !== "string") {
            throw new TypeError("argument 'query' must be a string");
        }
        adapter.setSearchQuery.call(this, query);
    };
    
    /**
     * Clear the search input
     */
    TopAppBar.prototype.clearSearch = function() {
        adapter.clearSearch.call(this);
    };
    
    /**
     * Focus the search input
     */
    TopAppBar.prototype.focusSearch = function() {
        adapter.focusSearch.call(this);
    };
    
    /**
     * Add navigation click listener
     * @param {Function} callback
     */
    TopAppBar.prototype.onNavigationClick = function(callback) {
        adapter.onNavigationClick.call(this, callback);
    };
    
    /**
     * Add action click listener
     * @param {Function} callback
     */
    TopAppBar.prototype.onActionClick = function(callback) {
        adapter.onActionClick.call(this, callback);
    };
    
    /**
     * Add search listener
     * @param {Function} callback
     */
    TopAppBar.prototype.onSearch = function(callback) {
        adapter.onSearch.call(this, callback);
    };
    
    /**
     * Add search clear listener
     * @param {Function} callback
     */
    TopAppBar.prototype.onSearchClear = function(callback) {
        adapter.onSearchClear.call(this, callback);
    };
    
    /**
     * Add search close listener
     * @param {Function} callback
     */
    TopAppBar.prototype.onSearchClose = function(callback) {
        adapter.onSearchClose.call(this, callback);
    };


    module.exports = TopAppBar;
}, null);

defineModule("SwitchAdapter", ["EventType", "CSSCore", "Child", "Children"], (function(global, requireModule, requireDynamic, requireLazy, module, exports, eventTypes) {
    'use strict';

    var classList = requireModule("components:classlist").switch;

    function getSwitch(switchInput) {
        var mSwitch = requireModule("Parent").byClass(switchInput, classList.container);
        return {
            switch: mSwitch,
            input: switchInput
        }
    }

    function addClass(switchElement, className) {
        var mSwitch = getSwitch(switchElement).switch;
        requireModule("CSSCore").addClass(mSwitch, className);
    }

    function removeClass(switchElement, className) {
        var mSwitch = getSwitch(switchElement).switch;
        requireModule("CSSCore").removeClass(mSwitch, className);
    }

    function setCheckedState(switchElement, checked) {
        var mSwitch = getSwitch(switchElement);

        checked ? requireModule("CSSCore").addClass(mSwitch.switch, classList.checked)
                : requireModule("CSSCore").removeClass(mSwitch.switch, classList.checked);
        switchElement.setAttribute("aria-checked", checked ? "true" : "false");
        switchElement.checked = checked;
    }

    function setDisabledState(switchElement, disabled) {
        var mSwitch = getSwitch(switchElement).switch;
        if (disabled) {
            requireModule("CSSCore").addClass(mSwitch, classList.disabled);
            switchElement.setAttribute("aria-disabled", "true");
            switchElement.disabled = true;
        } else {
            requireModule("CSSCore").removeClass(mSwitch, classList.disabled);
            switchElement.removeAttribute("aria-disabled");
            switchElement.disabled = false;
        }
    }

    var eventHandler = {
        handleFocusEvent: function(switchControl, event) {
            var type = event.type;
            var mSwitch = getSwitch(switchControl).switch;

            type == eventTypes.FOCUSIN ? 
                requireModule("CSSCore").addClass(mSwitch, classList.focused) : 
                requireModule("CSSCore").removeClass(mSwitch, classList.focused);
        },
        handleClickEvent: function(switchControl, event) {
            if (switchControl.getAttribute("aria-checked") == "true") {
                setCheckedState(switchControl, false);
            } else if (switchControl.getAttribute("aria-checked") == "false") {
                setCheckedState(switchControl, true);
            }
        },
        handleOnChange: function() {
            var callback = this.onChangeCallback;
            requireModule("Event").addEventListener(this._root, "change", function(event) {
                callback(event);
            });
        }
    };

    var adapter = {
        addClass: function(className) {
            addClass(this._root, className);
        },
        removeClass: function(className) {
            removeClass(this._root, className);
        },
        setChecked: function(checked) {
            setCheckedState(this._root, checked);
        },
        setDisabled: function(disabled) {
            setDisabledState(this._root, disabled);
        }
    };

    module.exports = {
        addClass: adapter.addClass,
        removeClass: adapter.removeClass,
        setChecked: adapter.setChecked,
        setDisabled: adapter.setDisabled,
        handleFocusEvent: eventHandler.handleFocusEvent,
        handleClickEvent: eventHandler.handleClickEvent,
        onChange: eventHandler.handleOnChange
    };
}), null);

defineModule("Switch", ["SwitchAdapter"], (function(global, requireModule, requireDynamic, requireLazy, module, exports, adapter) {
    'use strict';

    function mSwitch(id) {
        this._root = requireModule("$").fromIDOrElement(id);
    }
    mSwitch.prototype.addClass = function(className) {
        if (typeof className !== "string") throw new TypeError("argument 'className' must be a string type");
        adapter.addClass.call(this, className);
    };
    mSwitch.prototype.removeClass = function(className) {
        if (typeof className !== "string") throw new TypeError("argument 'className' must be a string type");
        adapter.removeClass.call(this, className);
    };
    mSwitch.prototype.setChecked = function(checked) {
        if (typeof checked !== "boolean") throw new TypeError("argument 'checked' must be a boolean type");
        adapter.setChecked.call(this, checked);
    };
    mSwitch.prototype.setDisabled = function(disabled) {
        if (typeof disabled !== "boolean") throw new TypeError("argument 'disabled' must be a boolean type");
        adapter.setDisabled.call(this, disabled);
    };
    mSwitch.prototype.handleChange = function(callback) {
        if (typeof callback !== "function") throw new TypeError("argument 'callback' must be a function");
        this.onChangeCallback = callback;
        adapter.onChange.call(this);
    };


    module.exports = mSwitch;
}), null);

defineModule("SnackbarAdapter", ["EventType", "CSSCore", "Child", "Children"], (function(global, requireModule, requireDynamic, requireLazy, module, exports, eventTypes) {
    'use strict';

    var classList = requireModule("components:classlist").snackbar;

    var queued = [];
    var currentlyShowing = null;
    var closeTimeoutId = null;
    // 1. ADD THIS VARIABLE: To store the element that needs focus returned.
    var elementToReturnFocusTo = null;

    var eventHandler = {
        handleKeyboardEvent: function(event) {
            if (event.key === 'Escape') {
                close();
            }
        }
    };

    function handleActionEvents(snackbarInstance) {
        var actionIcon, actionButton;
        var snackbarElement = snackbarInstance._root;

        // Does the action button has an event handler?
        if (snackbarInstance.actionButtonCallback) {
            actionButton = requireModule("Children").byClass(snackbarElement, classList.action)[0];
            if (!actionButton) return;
            requireModule("Event").addEventListener(actionButton, eventTypes.MOUSE.CLICK, snackbarInstance.actionButtonCallback);
        }

        // Does the icon button has an event handler?
        if (snackbarInstance.actionIconCallback) {
            actionIcon = requireModule("Children").byClass(snackbarElement, classList.dismiss)[0];
            if (!actionIcon) return;
            requireModule("Event").addEventListener(actionIcon, eventTypes.MOUSE.CLICK, snackbarInstance.actionIconCallback);
        }

    }

    function open(snackbarInstance) {
        if (currentlyShowing) {
            if (!queued.includes(snackbarInstance)) {
                 queued.push(snackbarInstance);
            }
            return;
        }

        // 2. ADD THIS LINE: Before showing the snackbar, save the currently focused element.
        elementToReturnFocusTo = document.activeElement;

        currentlyShowing = snackbarInstance;
        var snackbarElement = currentlyShowing._root;

        handleActionEvents(snackbarInstance, true);

        var label = snackbarElement.querySelector('.' + classList.label);
        if (label && currentlyShowing.text) {
            label.textContent = currentlyShowing.text;
        }

        var actionButton = snackbarElement.querySelector('.' + classList.action);
        if (actionButton && currentlyShowing.actionButtonText) {
            actionButton.textContent = currentlyShowing.actionButtonText;
        }

        // snackbarElement.setAttribute('aria-hidden', 'false');
        // snackbarElement.setAttribute('aria-live', 'assertive');

        if (currentlyShowing.closeOnEscape) {
            document.addEventListener('keydown', eventHandler.handleKeyboardEvent);
        }

        requireModule("CSSCore").addClass(snackbarElement, classList.opening);

        setTimeout(function() {
            requireModule("CSSCore").removeClass(snackbarElement, classList.opening);
            requireModule("CSSCore").addClass(snackbarElement, classList.open);
        }, 10);

        closeTimeoutId = setTimeout(function() {
            close();
        }, currentlyShowing.timeoutMs || 5000);
    }

    function close() {
        if (!currentlyShowing) {
            return;
        }

        var closingSnackbar = currentlyShowing;
        var snackbarElement = closingSnackbar._root;
        var surfaceElement  = requireModule("Child").byClass(snackbarElement, classList.surface);

        if (!surfaceElement) return;

        if (closeTimeoutId) {
            clearTimeout(closeTimeoutId);
            closeTimeoutId = null;
        }

        currentlyShowing = null;

        requireModule("CSSCore").removeClass(snackbarElement, classList.open);
        requireModule("CSSCore").addClass(snackbarElement, classList.closing);

        surfaceElement.addEventListener('transitionend', function handler() {
            surfaceElement.removeEventListener('transitionend', handler);

            // 3. ADD THIS BLOCK: Move focus BEFORE hiding the element.
            if (elementToReturnFocusTo) {
                elementToReturnFocusTo.focus();
            }

            requireModule("CSSCore").removeClass(snackbarElement, classList.closing);
            // snackbarElement.setAttribute('aria-hidden', 'true');

            if (closingSnackbar.closeOnEscape) {
                document.removeEventListener('keydown', eventHandler.handleKeyboardEvent);
            }

            // Handling onClose
            if (closingSnackbar.closeCallback) {
                var callback = closingSnackbar.closeCallback;
                callback();
            }

            if (queued.length > 0) {
                var nextSnackbar = queued.shift();
                open(nextSnackbar);
            }
        });
    }

    var adapter = {
        open: function(snackbarInstance) {
            open(snackbarInstance);
        },
        close: function() {
            close();
        },
        handleClickEvent: function() {
            close();
        }
    };

    module.exports = adapter;
}), null);

defineModule("Snackbar", ["SnackbarAdapter"], (function(global, requireModule, requireDynamic, requireLazy, module, exports, adapter) {
    'use strict';

    function snackbar(id) {
        this._root = requireModule("$").fromIDOrElement(id);
        this.closeOnEscape = false;
        this.timeoutMs = 5000;
    }
    snackbar.prototype.setText = function(text) {
        if (typeof text !== "string") throw new TypeError("argument 'text' must be a string type");
        this.text = text;
        return this;
    };
    snackbar.prototype.setActionButtonText = function(text) {
        if (typeof text !== "string") throw new TypeError("argument 'text' must be a string type");
        this.actionButtonText = text;
        return this;
    };
    snackbar.prototype.open = function() {
        // Pass the current instance 'this' to the adapter's open method.
        adapter.open(this);
    };
    snackbar.prototype.close = function() {
        // The close function now intelligently closes the currently active snackbar.
        adapter.close();
    };
    snackbar.prototype.setCloseOnEscape = function(closeOnEscape) {
        if (typeof closeOnEscape !== "boolean") throw new TypeError("argument 'closeOnEscape' must be a boolean type");
        this.closeOnEscape = closeOnEscape;
        return this;
    };
    snackbar.prototype.setTimeoutMs = function(timeoutMs) {
         if (typeof timeoutMs !== "number") throw new TypeError("argument 'timeoutMs' must be a number type");
         if (timeoutMs < 4000 || timeoutMs > 10000) throw new RangeError("argument 'timeoutMs' must be between 4000 and 10000");
         this.timeoutMs = timeoutMs;
         return this;
    };
    snackbar.prototype.handleActionButtonClick = function(callback) {
        if (typeof callback !== "function") throw new TypeError("argument 'callback' must be a function");
        this.actionButtonCallback = callback;
        return this;
    };
    snackbar.prototype.handleActionIconClick = function(callback) {
        if (typeof callback !== "function") throw new TypeError("argument 'callback' must be a function");
        this.actionIconCallback = callback;
        return this;
    };
    snackbar.prototype.onClose = function(callback) {
        if (typeof callback !== "function") throw new TypeError("argument 'callback' must be a function");
        this.closeCallback = callback;
        return this;
    };

    module.exports = snackbar;
}), null);

defineModule("MenuAdapter", ["EventType", "CSSCore", "Child", "Children"], (function(global, requireModule, requireDynamic, requireLazy, module, exports, eventTypes) {
    'use strict';

    var classList = requireModule("components:classlist").menu;
    var listClassList = requireModule("components:classlist").list;
    var selectClassList = requireModule("components:classlist").select;

    var state = requireModule("components:state");
    var CSSCore = requireModule("CSSCore");
    var Parent = requireModule("Parent");
    var $ = requireModule("$");
    var isAnimating = false;
    
    /**
     * Calculates the position of the menu surface relative to its trigger element.
     *
     * @param {HTMLElement} menu The menu surface element to position.
     * @returns {Object} A style object for positioning the menu.
     */
    function getMenuPosition(menu) {
        var anchor = requireModule("Parent").byClass(menu, classList.anchor);

        // --- Find the specific trigger element inside the anchor ---
        var trigger = null;
        for (var i = 0; i < anchor.children.length; i++) {
            // The trigger is the first child that is not the menu itself.
            if (anchor.children[i] !== menu) {
                trigger = anchor.children[i];
                break;
            }
        }

        if (!trigger) {
            console.error("Could not find a trigger element inside the menu anchor.");
            return {}; // Can't position without a trigger
        }

        var isExposed = requireModule("CSSCore").hasClass(menu, classList.exposed);

        requireModule("CSSCore").addClass(menu, 'c_menu-surface--measuring');
        var menuRect = menu.getBoundingClientRect();
        requireModule("CSSCore").removeClass(menu, 'c_menu-surface--measuring');

        var triggerRect = trigger.getBoundingClientRect(); // Use trigger's rect for viewport checks
        var viewportHeight = window.innerHeight;
        var Y_PADDING = 16;
        var styles = {};


        // --- 1. Horizontal Position & Width ---
        var alignEnd = requireModule("CSSCore").hasClass(menu, classList.end_aligned);

        if (isExposed) {
            // Exposed menus match trigger width
            styles.width = trigger.offsetWidth + 'px';

            if (alignEnd) {
                // Right edge aligned with trigger
                styles.left = 'auto';
                styles.right = (anchor.offsetWidth - trigger.offsetLeft - trigger.offsetWidth) + 'px';
            } else {
                // Left edge aligned with trigger
                styles.left = trigger.offsetLeft + 'px';
                styles.right = 'auto';
            }

        } else {
            // Standard dropdown menu

            if (alignEnd) {
                // Align menu right edge with trigger right edge
                styles.left = 'auto';
                styles.right = (anchor.offsetWidth - trigger.offsetLeft - trigger.offsetWidth) + 'px';
            } else {
                // Default start alignment
                styles.left = trigger.offsetLeft + 'px';
                styles.right = 'auto';
            }
        }

        // --- 2. Vertical Position (relative to the trigger) ---
        var spaceBelow = viewportHeight - triggerRect.bottom;
        var spaceAbove = triggerRect.top;
        var openDownwards = (spaceBelow >= menuRect.height || spaceBelow >= spaceAbove);

        if (openDownwards) {
            styles.transformOrigin = !alignEnd ? 'top left' : 'top right';
            styles.maxHeight = Math.max(0, spaceBelow - Y_PADDING) + 'px';
            styles.bottom = 'auto';
            // For exposed, top starts at the trigger's bottom edge.
            styles.top = isExposed ? (trigger.offsetTop + trigger.offsetHeight) + 'px' : '0px';
        } else { // Open upwards
            styles.transformOrigin = !alignEnd ? 'bottom left' : 'bottom right';
            styles.maxHeight = Math.max(0, spaceAbove - Y_PADDING) + 'px';
            styles.top = 'auto';
            // For exposed, bottom is positioned relative to the trigger's top edge.
            styles.bottom = isExposed ? (anchor.offsetHeight - trigger.offsetTop) + 'px' : '0px';
        }

        return styles;
    }
    
    function _getListItemAtIndex(a, b) {
        return a[b];
    }

    function open() {
        if (isAnimating) {
            return;
        }

        var menuEl = this._root;
        var triggerEl = this._trigger; // Can be null for programmatic open

        // Handle toggle behavior for exposed dropdowns
        var isExposed = CSSCore.hasClass(menuEl, classList.exposed);
        if (isExposed && state.ACTIVE_MENU === menuEl) {
            close();
            return;
        }

        // If another menu is open, close it first.
        if (state.ACTIVE_MENU && state.ACTIVE_MENU !== menuEl) {
            close();
        }

        isAnimating = true;
        state.ACTIVE_MENU = menuEl;
        state.ACTIVE_MENU_TRIGGER = triggerEl; // Store the trigger

        // 1. Calculate and apply position
        var styles = getMenuPosition(menuEl);
        for (var key in styles) {
            menuEl.style[key] = styles[key];
        }

        // 2. Start the opening animation
        CSSCore.addClass(menuEl, classList.opening);

        // 3. Use requestAnimationFrame to apply the final state in the next frame
        requestAnimationFrame(function() {
            CSSCore.addClass(menuEl, classList.open);

            // 4. After the animation duration (120ms), remove the opening class.
            setTimeout(function() {
                CSSCore.removeClass(menuEl, classList.opening);
                isAnimating = false;
            }, 120);
        });

        // 5. Listen for clicks outside to close the menu.
        // document.addEventListener('click', handleClickOutside, true);
    }

    /**
     * Closes the currently active menu.
     */
    function close() {
        if (!state.ACTIVE_MENU || isAnimating) {
            return;
        }

        isAnimating = true;
        var menuToClose = state.ACTIVE_MENU;

        CSSCore.addClass(menuToClose, classList.closing);
        CSSCore.removeClass(menuToClose, classList.open);

        // Wait for the closing animation (75ms) to finish before cleaning up.
        setTimeout(function() {
            CSSCore.removeClass(menuToClose, classList.closing);
            isAnimating = false;
            
            // Removing activated class from select parent element
            var selectElement = requireModule("Parent").byClass(menuToClose, requireModule("components:classlist").select.container);
            if (selectElement) {
                CSSCore.removeClass(selectElement, requireModule("components:classlist").select.activated);
                var selectedText = requireModule("Children").byClass(selectElement, selectClassList.selected_text)[0];
                if (selectedText) {
                    selectedText.setAttribute("aria-expanded", "false");
                }
            }

        }, 75);

        // Clean up global state and event listeners
        state.ACTIVE_MENU = null;
        state.ACTIVE_MENU_TRIGGER = null;
        // document.removeEventListener('click', handleClickOutside, true);
    }
    
    /**
     * Returns the menu items
     * @param {HTMLElement} menu
     */
    function getItems(menu) {
        return requireModule("Children").byClass(menu, classList.item);
    }

    function resetMenu(menu) {
        var menu = new(requireModule("Menu"))(menu);
        menu.getItems().forEach(function(item) {
            requireModule("CSSCore").removeClass(item, listClassList.activated);
        });
    }

    var eventHandler = {
    };

    var adapter = {
        open: function() {
            // Programmatic open has no trigger.
            open.call({
                _root: this._root,
                _trigger: null
            });
        },
        // This version is for programmatic calls on a specific instance (e.g., menu.close())
        // It remains unchanged.
        closeInstance: function() { 
            if (state.ACTIVE_MENU === this._root) {
                close.call(this);
            }
        },

        close: close, 
        /**
         * Main click handler for menu triggers.
         * @param {HTMLElement} trigger The element that was clicked.
         */
        handleClickEvent: function(trigger) {
            var menuId = trigger.getAttribute('data-open-menu');
            if (!menuId) return;

            var menuEl = $.fromIDOrElement(menuId);
            if (!menuEl) {
                console.error('Menu with ID "' + menuId + '" not found.');
                return;
            }

            // Call open with both the menu and its trigger.
            open.call({
                _root: menuEl,
                _trigger: trigger
            });
        },
        getItems: function() {
            return getItems(this._root);
        },
        getItemCount: function() {
            return getItems(this._root).length;
        },
        setAbsolutePosition: function(x, y) {
            if (typeof x !== 'number' || typeof y !== 'number') {
                console.error('setAbsolutePosition requires numeric x and y coordinates.');
                return;
            }
            this._root.style.position = 'absolute';
            this._root.style.left = x + 'px';
            this._root.style.top = y + 'px';
        },

        setFixedPosition: function(isFixed) {
            if (typeof isFixed !== 'boolean') {
                console.error('setFixedPosition requires a boolean value.');
                return;
            }
            var fixedClass = classList.menu.fixed;
            if (isFixed) {
                CSSCore.addClass(this._root, fixedClass);
            } else {
                CSSCore.removeClass(this._root, fixedClass);
            }
        },

        setSelectedIndex: function(index) {
            var selectedClass = classList.item_selected;
            var currentlySelected = this._root.querySelector('.' + selectedClass);
            if (currentlySelected) {
                CSSCore.removeClass(currentlySelected, selectedClass);
            }

            var item = _getListItemAtIndex(this.items, index);
            if (item) {
                CSSCore.addClass(item, selectedClass);
            }
        },
        
        getSelectedIndex: function() {
            var selectedClass = classList.item_selected;
            var currentlySelected = this._root.querySelector('.' + selectedClass);
            return this.items.indexOf(currentlySelected);
        },

        setEnabled: function(index, isEnabled) {
            if (typeof isEnabled !== 'boolean') {
                console.error('setEnabled requires a boolean isEnabled value.');
                return;
            }
            var item = _getListItemAtIndex(this.items, index);
            if (!item) return;

            var disabledClass = classList.list_item.disabled;
            if (isEnabled) {
                CSSCore.removeClass(item, disabledClass);
                item.setAttribute('tabindex', '0');
                item.removeAttribute('aria-disabled');
            } else {
                CSSCore.addClass(item, disabledClass);
                item.setAttribute('tabindex', '-1');
                item.setAttribute('aria-disabled', 'true');
            }
        },

        addClassToElementAtIndex: function(index, className) {
            if (typeof className !== 'string' || className.trim() === '') {
                console.error('addClassToElementAtIndex requires a non-empty string className.');
                return;
            }
            var item = _getListItemAtIndex(this.items, index);
            if (item) {
                CSSCore.addClass(item, className);
            }
        },

        removeClassFromElementAtIndex: function(index, className) {
            if (typeof className !== 'string' || className.trim() === '') {
                console.error('removeClassFromElementAtIndex requires a non-empty string className.');
                return;
            }
            var item = _getListItemAtIndex(this.items, index);
            if (item) {
                CSSCore.removeClass(item, className);
            }
        },

        elementContainsClass: function(element, className) {
            if (!(element instanceof Element)) {
                console.error('elementContainsClass requires a valid DOM Element.');
                return false;
            }
            if (typeof className !== 'string' || className.trim() === '') {
                return false;
            }
            return CSSCore.hasClass(element, className);
        },

        addAttributeToElementAtIndex: function(index, attr, value) {
            if (typeof attr !== 'string' || attr.trim() === '') {
                console.error('addAttributeToElementAtIndex requires a non-empty string attribute name.');
                return;
            }
            var item = _getListItemAtIndex(this.items, index);
            if (item) {
                item.setAttribute(attr, value);
            }
        },

        removeAttributeFromElementAtIndex: function(index, attr) {
            if (typeof attr !== 'string' || attr.trim() === '') {
                console.error('removeAttributeFromElementAtIndex requires a non-empty string attribute name.');
                return;
            }
            var item = _getListItemAtIndex(this.items, index);
            if (item) {
                item.removeAttribute(attr);
            }
        },
        reset: resetMenu
    };

    module.exports = adapter;
}), null);

defineModule("Menu", ["MenuAdapter"], (function(global, requireModule, requireDynamic, requireLazy, module, exports, adapter) {
    'use strict';

    function menu(id) {
        this._root = requireModule("$").fromIDOrElement(id);
        this.items = this.getItems();
    }

    menu.prototype.open = function() {
        adapter.open.call(this);
    };

    menu.prototype.close = function() {
        adapter.closeInstance.call(this);
    };

    menu.prototype.getItems = function() {
        return adapter.getItems.call(this);
    };

    menu.prototype.getItemCount = function() {
        return adapter.getItemCount.call(this);
    };
    menu.prototype.setAbsolutePosition = function(x, y) {
        adapter.setAbsolutePosition.call(this, x, y);
    };

    menu.prototype.setFixedPosition = function(isFixed) {
        adapter.setFixedPosition.call(this, isFixed);
    };

    menu.prototype.setSelectedIndex = function(index) {
        adapter.setSelectedIndex.call(this, index);
    };
    
    menu.prototype.getSelectedIndex = function() {
        return adapter.getSelectedIndex.call(this);
    };

    menu.prototype.setEnabled = function(index, isEnabled) {
        adapter.setEnabled.call(this, index, isEnabled);
    };

    menu.prototype.addClassToElementAtIndex = function(index, className) {
        adapter.addClassToElementAtIndex.call(this, index, className);
    };

    menu.prototype.removeClassFromElementAtIndex = function(index, className) {
        adapter.removeClassFromElementAtIndex.call(this, index, className);
    };
    
    menu.prototype.elementContainsClass = function(element, className) {
        // This method doesn't rely on `this`, so a direct call is fine.
        return adapter.elementContainsClass(element, className);
    };

    menu.prototype.addAttributeToElementAtIndex = function(index, attr, value) {
        adapter.addAttributeToElementAtIndex.call(this, index, attr, value);
    };
    
    menu.prototype.removeAttributeFromElementAtIndex = function(index, attr) {
        adapter.removeAttributeFromElementAtIndex.call(this, index, attr);
    };

    menu.prototype.reset = function() {
        adapter.reset.call(this);
    };

    module.exports = menu;
}), null);

defineModule("NavigationDrawerAdapter", ["EventType", "CSSCore", "Child", "Children"], (function(global, requireModule, requireDynamic, requireLazy, module, exports, eventTypes) {
    'use strict';

    var classList = requireModule("components:classlist").navigation_drawer;

    /**
     *
     * @param {HTMLElement} item
     * @param {boolean} active
     */
    function setActiveState(item, active) {
        var listClassList = requireModule("components:classlist").list;
        active ? requireModule("CSSCore").addClass(item, listClassList.item_activated)
               : requireModule("CSSCore").removeClass(item, listClassList.item_activated);
        item.setAttribute("tabindex", active ? "0" : "-1");
        item.setAttribute("aria-selected", active ? "true" : "false");
    }

    function open() {}

    function close() {}

    var eventHandler = {

        handleClickEvent: function(itemElement, event) {
            if (itemElement.tagName.toLowerCase() == "a" && itemElement.href == "#") {
                event.preventDefault();
            }
        }
    };

    var adapter = {
        handleClickEvent: eventHandler.handleClickEvent,
        open: function() {}
    };

    module.exports = adapter;
}), null);

defineModule("NavigationDrawer", ["SnackbarAdapter"], (function(global, requireModule, requireDynamic, requireLazy, module, exports, adapter) {
    'use strict';

    function navigationDrawer(id) {
        this._root = requireModule("$").fromIDOrElement(id);
        this.closeOnEscape = false;
    }
    navigationDrawer.prototype.open = function() {
        // Pass the current instance 'this' to the adapter's open method.
        adapter.open(this);
    };
    navigationDrawer.prototype.close = function() {
        // The close function now intelligently closes the currently active snackbar.
        adapter.close();
    };
    navigationDrawer.prototype.setCloseOnEscape = function(closeOnEscape) {
        if (typeof closeOnEscape !== "boolean") throw new TypeError("argument 'closeOnEscape' must be a boolean type");
        this.closeOnEscape = closeOnEscape;
        return this;
    };
    snackbar.prototype.onClose = function(callback) {
        if (typeof callback !== "function") throw new TypeError("argument 'callback' must be a function");
        this.closeCallback = callback;
        return this;
    };

    module.exports = snackbar;
}), null);

defineModule("DialogAdapter", ["EventType", "CSSCore", "Child", "Children"], (function(global, requireModule, requireDynamic, requireLazy, module, exports, eventTypes) {
    'use strict';

    var classList = requireModule("components:classlist").dialog;
    var state = requireModule("components:state");
    var addBodyOverflow = false;

    var selected = [];

    /**
     * A utility for trapping focus within a given element.
     */
    var FocusTrap = {
        _container: null,
        _focusableElements: [],
        _firstFocusableElement: null,
        _lastFocusableElement: null,
        _lastFocusedElement: null, // Element focused before opening the trap

        /**
         * Activates the focus trap on the given dialog container.
         * @param {HTMLElement} container The dialog element.
         */
        activate: function(container) {
            this._container = container;
            this._lastFocusedElement = document.activeElement;

            this._focusableElements = Array.from(
                container.querySelectorAll(
                    'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
                )
            );

            if (this._focusableElements.length === 0) return;

            this._firstFocusableElement = this._focusableElements[0];
            this._lastFocusableElement = this._focusableElements[this._focusableElements.length - 1];

            // Use requestAnimationFrame to ensure the element is focusable
            window.requestAnimationFrame(() => {
                this._firstFocusableElement.focus();
            });

            this._container.addEventListener('keydown', this._handleKeydown.bind(this));
        },

        /**
         * Deactivates the focus trap and restores focus to the previously focused element.
         */
        deactivate: function() {
            if (this._container) {
                this._container.removeEventListener('keydown', this._handleKeydown.bind(this));
            }
            if (this._lastFocusedElement) {
                this._lastFocusedElement.focus();
            }
            this._container = null;
        },

        _handleKeydown: function(event) {
            if (event.key !== 'Tab') return;

            if (event.shiftKey) { // Tabbing backwards
                if (document.activeElement === this._firstFocusableElement) {
                    event.preventDefault();
                    this._lastFocusableElement.focus();
                }
            } else { // Tabbing forwards
                if (document.activeElement === this._lastFocusableElement) {
                    event.preventDefault();
                    this._firstFocusableElement.focus();
                }
            }
        }
    };


    function open() {

        var container = this._root;
        console.log(container);

        if (!container) return;


        state.ACTIVE_DIALOG = this; // Store the instance, not just the element

        if (addBodyOverflow) {
            document.body.style.overflow = "hidden";
        }

        requireModule("CSSCore").addClass(container, classList.opening);

        // Allow the browser to apply the 'opening' class styles
        setTimeout(function() {
            // Now, add the 'open' class to trigger the transition
            requireModule("CSSCore").addClass(container, classList.open);
            FocusTrap.activate(container);

            // Check if a open callback was provided on the instance
            if (typeof this.openCallback === 'function') {
                this.openCallback();
            }

            // Clean up the 'opening' class after the animation is complete
            setTimeout(function() {
                requireModule("CSSCore").removeClass(container, classList.opening);
            }, 150); // This timeout should match your CSS transition duration
        }, 1); // A very short timeout to ensure the next frame
    }

    function close() {
        var dialogInstance = state.ACTIVE_DIALOG;
        if (!dialogInstance) return;

        var container = dialogInstance._root;

        if (addBodyOverflow) {
            document.body.style.overflow = "auto";
        }
        
        FocusTrap.deactivate(); // Deactivate trap immediately

        // Add the closing class to apply the transition properties
        requireModule("CSSCore").addClass(container, classList.closing);

        // Immediately remove the open class to trigger the animation
        requireModule("CSSCore").removeClass(container, classList.open);

        // After the transition finishes, remove the closing class
        setTimeout(function() {
             // Check if a close callback was provided on the instance
            if (typeof dialogInstance.closeCallback === 'function') {
                dialogInstance.closeCallback();
            }

            requireModule("CSSCore").removeClass(container, classList.opening);

            // Does the dialog has a form, if so, reset it, along with the default components states
            // requireModule("components:form").reset(container);

            // Setting disabled state to positive button if the dialog is a comfirmation type
            if (requireModule("CSSCore").hasClass(container, classList.confirmation)) {
                var positiveButton = requireModule("Children").byClass(container, classList.positive_button)[0];
                if (positiveButton) {
                    positiveButton.disabled = true;
                }
            }

            requireModule("CSSCore").removeClass(container, classList.closing);
            requireModule("components:state").ACTIVE_DIALOG = null;
        }, 95); // Match your transition duration (75ms) + a small buffer
    }

    var eventHandler = {
        handleClickEvent: function() {
            var dialogInstance = state.ACTIVE_DIALOG;
            if (!dialogInstance) return;
            
            var dialog = dialogInstance._root;
            var isConfirmationDialog = requireModule("CSSCore").hasClass(dialog, classList.confirmation);

            // This logic only applies to confirmation dialogs that contain a list of choices.
            if (isConfirmationDialog) {
                var positiveButton = requireModule("Children").byClass(dialog, classList.positive_button)[0];
                if (!positiveButton) return; // Exit if there's no positive button to update.

                var content = requireModule("Children").byClass(dialog, classList.content)[0];
                if (!content) return;

                // Find all selectable inputs (checkbox, radio, switch) within the dialog's content area.
                var selectableInputs = content.querySelectorAll(
                    'input[type="checkbox"], input[type="radio"]'
                );

                // Check if at least one of the inputs is checked.
                var isAnyItemSelected = Array.prototype.some.call(selectableInputs, function(input) {
                    return input.checked;
                });

                // Enable or disable the positive button based on the result.
                if (isAnyItemSelected) {
                    positiveButton.removeAttribute('disabled');
                } else {
                    positiveButton.setAttribute('disabled', 'true');
                }
            } else {
                // For non-confirmation dialogs, maintain the original behavior of closing on click.
                close();
            }
        },
        handleActionButtonClick: function(button, event) {
            var dialogInstance = state.ACTIVE_DIALOG;
            if (!dialogInstance) return;

            var isPositiveButton = requireModule("CSSCore").hasClass(button, classList.positive_button);

            if (isPositiveButton && /*button.getAttribute("type") == "submit" &&*/ button.hasAttribute("form")) {
                return;
            }

            if (isPositiveButton) {
                if (typeof dialogInstance.acceptCallback === "function") {

                    var done = function() {
                        close()
                    };

                    var result = dialogInstance.acceptCallback(done, getSelectedOptions);
                } else {
                    // No callback defined, default behavior is to close
                    close();
                }
            } else {
                close();
            }
        }
    };
    
    /**
     * Returns an array of the selected input elements (checkbox, radio, switch)
     * from a list within the dialog's content.
     * @returns {Array<HTMLInputElement>} An array of the selected input elements.
     */
    function getSelectedOptions() {
        var dialogInstance = state.ACTIVE_DIALOG;
        if (!dialogInstance) return;
        
        var dialog = dialogInstance._root;
        // Find the main content area of the dialog.
        var content = requireModule("Children").byClass(dialog, classList.content)[0];
        // If there is no content area, return an empty array.
        if (!content) {
            return [];
        }

        // Find all checkbox and radio inputs that are currently checked.
        var selectedInputs = content.querySelectorAll(
            'input[type="checkbox"]:checked, input[type="radio"]:checked'
        );

        // Convert the NodeList of elements to a true Array and return it.
        return Array.from(selectedInputs);
    };
        
    function addClass(dialogInstance, className, addToBody) {
        var dialog = dialogInstance._root;
        var target = addToBody ? document.body : dialog;
        requireModule("CSSCore").addClass(target, className);
    }
    function removeClass(dialogInstance, className, addToBody) {
        var dialog = dialogInstance._root;
        var target = addToBody ? document.body : dialog;
        requireModule("CSSCore").removeClass(target, className);
    }
    function hasClass(dialogInstance, className, addToBody) {
        var dialog = dialogInstance._root;
        var target = addToBody ? document.body : dialog;
        return requireModule("CSSCore").hasClass(target, className);
    }

    var adapter = {
        handleClickEvent: eventHandler.handleClickEvent,
        handleActionButtonClick: eventHandler.handleActionButtonClick,
        getSelectedOptions: getSelectedOptions,
        addClass: addClass,
        removeClass: removeClass,
        hasClass: hasClass,
        open: function() {
            open.call(this);
        },
        close: function() {
            close.call();
        }
    };

    module.exports = adapter;
}), null);

defineModule("Dialog", ["DialogAdapter"], (function(global, requireModule, requireDynamic, requireLazy, module, exports, adapter) {
    'use strict';

    function dialog(id) {
        this._root = requireModule("$").fromIDOrElement(id);
        this.closeOnEscape = true;
    }
    dialog.prototype.addClass = function(className) {
        if (typeof className !== "string") throw new TypeError("argument 'className' must be a string type");
        adapter.addClass(this, className);
    };
    dialog.prototype.removeClass = function(className) {
        if (typeof className !== "string") throw new TypeError("argument 'className' must be a string type");
        adapter.removeClass(this, className);
    };
    dialog.prototype.hasClass = function(className) {
        if (typeof className !== "string") throw new TypeError("argument 'className' must be a string type");
        adapter.hasClass(this, className);
    };
    dialog.prototype.addBodyClass = function(className) {
        if (typeof className !== "string") throw new TypeError("argument 'className' must be a string type");
        adapter.addClass(this, className, true);
    };
    dialog.prototype.removeBodyClass = function(className) {
        if (typeof className !== "string") throw new TypeError("argument 'className' must be a string type");
        adapter.removeClass(this, className, true);
    };
    dialog.prototype.getSelectedOptions = function() {
        return adapter.getSelectedOptions.call(this);
    };
    dialog.prototype.onAccept = function(callback) {
        if (typeof callback !== "function") throw new TypeError("argument 'callback' must be a function");
        this.acceptCallback = callback;
        return this;
    };
    dialog.prototype.open = function() {
        // Pass the current instance 'this' to the adapter's open method.
        adapter.open.call(this);
    };
    dialog.prototype.onOpen = function(callback) {
        if (typeof callback !== "function") throw new TypeError("argument 'callback' must be a function");
        this.openCallback = callback;
        return this;
    };
    dialog.prototype.close = function() {
        // The close function now intelligently closes the currently active dialog
        adapter.close();
    };
    dialog.prototype.onClose = function(callback) {
        if (typeof callback !== "function") throw new TypeError("argument 'callback' must be a function");
        this.closeCallback = callback;
        return this;
    };
    dialog.prototype.setCloseOnEscape = function(closeOnEscape) {
        if (typeof closeOnEscape !== "boolean") throw new TypeError("argument 'closeOnEscape' must be a boolean type");
        this.closeOnEscape = closeOnEscape;
        return this;
    };

    module.exports = dialog;
}), null);

defineModule("initComponents", ["EventType"], (function(global, requireModule, requireDynamic, requireLazy, module, exports, eventTypes) {
    'use strict';
    var classList = requireModule("components:classlist");

    function handleChangeEvent(event) {
        var domEvent = new(requireModule("DOMEvent"))(event);
        var target   = domEvent.target;
        var type     = domEvent.type;

        var textfieldInput = requireModule("Parent").byClass(target, classList.textfield.input);
        if (textfieldInput != null) {
            requireModule("TextfieldAdapter").handleChangeEvent(target, domEvent.event);
        }
    }

    function handleFocusEvent(event) {
        var domEvent = new(requireModule("DOMEvent"))(event);
        var target   = domEvent.target;
        var type     = domEvent.type;

        if (type == eventTypes.FOCUSIN) {
            var topAppBarSearchInput = requireModule("Parent").byAttribute(target, 'data-search-input');
            if (topAppBarSearchInput) {
                requireModule("TopAppBarAdapter").handleSearchFocus(topAppBarSearchInput, domEvent.event);
            }
        }

        if (type == eventTypes.FOCUSOUT) {
            var topAppBarSearchInput = requireModule("Parent").byAttribute(target, 'data-search-input');
            if (topAppBarSearchInput) {
                requireModule("TopAppBarAdapter").handleSearchBlur(topAppBarSearchInput, domEvent.event);
            }
        }

        var checkbox = requireModule("Parent").byClass(target, classList.checkbox.input);
        if (checkbox) {
            requireModule("CheckboxAdapter").handleFocusEvent(target, domEvent.event);
        }

        var mSwitch = requireModule("Parent").byClass(target, classList.switch.input);
        if (mSwitch) {
            requireModule("SwitchAdapter").handleFocusEvent(target, domEvent.event);
        }

        var listItem = requireModule("Parent").byClass(target, classList.list.item);
        if (listItem) {
            requireModule("ListAdapter").handleFocusEvent(target, domEvent.event);
        }

        var tab = requireModule("Parent").byClass(target, classList.tab.container);
        if (tab) {
            requireModule("TabAdapter").handleFocusEvent(target, domEvent.event);
        }

        var textfieldInput = requireModule("Parent").byClass(target, classList.textfield.input);
        if (textfieldInput !== null) {
            requireModule("TextfieldAdapter").handleFocusEvent(target, domEvent.event);
        }
        
        var mSelectText = requireModule("Parent").byClass(target, classList.select.selected_text);
        if (mSelectText) {
            requireModule("SelectAdapter").handleFocusEvent(target, domEvent.event);
        }
    }

    function handleMouseEvent(event) {
        var domEvent = new(requireModule("DOMEvent"))(event);
        var target   = domEvent.target;
        var type     = domEvent.type;

        if (type == eventTypes.MOUSE.CLICK) {
            var state = requireModule("components:state");

            if (state.ACTIVE_MENU) {
                var isClickOnTrigger = requireModule("Parent").byAttribute(target, 'data-open-menu');
                var isClickInsideMenu = state.ACTIVE_MENU.contains(target);

                if (!isClickOnTrigger && !isClickInsideMenu) {
                    requireModule("MenuAdapter").close();
                    return; 
                }
            }

            var topAppBarSearchTrigger = requireModule("Parent").byAttribute(target, 'data-search-trigger');
            if (topAppBarSearchTrigger) {
                requireModule("TopAppBarAdapter").handleSearchTrigger(topAppBarSearchTrigger, domEvent.event);
                return;
            }

            var topAppBarNavigationIcon = requireModule("Parent").byAttribute(target, 'data-navigation-icon');
            if (topAppBarNavigationIcon) {
                requireModule("TopAppBarAdapter").handleNavigationClick(topAppBarNavigationIcon, domEvent.event);
                return;
            }

            var topAppBarSearchClose = requireModule("Parent").byAttribute(target, 'data-search-close');
            if (topAppBarSearchClose) {
                requireModule("TopAppBarAdapter").handleSearchClose(topAppBarSearchClose, domEvent.event);
                return;
            }

            var menuTrigger = requireModule("Parent").byAttribute(target, 'data-open-menu');
            if (menuTrigger) {
                requireModule("MenuAdapter").handleClickEvent(menuTrigger);
                return;
            }

            var scrim = requireModule("Parent").byClass(target, classList.dialog.scrim);
            if (scrim) {
                if (requireModule("components:state").ACTIVE_DIALOG !== null) {
                    requireModule("DialogAdapter").close();
                }
            }

            var chipRemoveIcon = requireModule("Parent").byClass(target, classList.chip.chip_icon_trailing);
            if (chipRemoveIcon) {
                requireModule("ChipSetAdapter").handleRemoveIconClickEvent(chipRemoveIcon, domEvent.event);
                return;
            }

            var chip = requireModule("Parent").byClass(target, classList.chip.chip);
            if (chip) {
                requireModule("ChipSetAdapter").handleClickEvent(chip, domEvent.event);
            }

            var switchElement = requireModule("Parent").byClass(target, classList.switch.input);
            if (switchElement) {
                requireModule("SwitchAdapter").handleClickEvent(switchElement, domEvent.event);
            }

            var dialogActionButton = requireModule("Parent").byClass(target, classList.dialog.button);
            if (dialogActionButton) {
                requireModule("DialogAdapter").handleActionButtonClick(dialogActionButton, domEvent.event);
            }

            var iconButton = requireModule("Parent").byClass(target, classList.icon_button.container);
            if (iconButton) {
                requireModule("IconButtonAdapter").handleClickEvent(iconButton, domEvent.event);
            }

            var listItem = requireModule("Parent").byClass(target, classList.list.item);
            if (listItem) {
                requireModule("ListAdapter").handleClickEvent(listItem, domEvent.event);
                
                var selectElement = requireModule("Parent").byClass(listItem, classList.select.container);
                if (selectElement) {
                    requireModule("SelectAdapter").handleClickEventFromMenu(listItem, domEvent.event);
                    return;
                }

                if (requireModule("Parent").byClass(listItem, classList.navigation_drawer.container)) {
                    requireModule("NavigationDrawerAdapter").handleClickEvent(listItem, domEvent.event);
                }
                
                var dialog = requireModule("Parent").byClass(listItem, classList.dialog.dialog);
                if (dialog) {
                    requireModule("DialogAdapter").handleClickEvent(listItem, domEvent.event);
                }
                
                var menu = requireModule("Parent").byClass(listItem, classList.menu.container);
                if (menu && requireModule("components:state").ACTIVE_MENU) {
                    requireModule("MenuAdapter").close();
                }
            }

            var snackbarrDismissButton = requireModule("Parent").byClass(target, classList.snackbar.dismiss);
            var snackbarActionButton = requireModule("Parent").byClass(target, classList.snackbar.action);
            if (snackbarrDismissButton || snackbarActionButton) {
                requireModule("SnackbarAdapter").handleClickEvent(snackbarrDismissButton, domEvent.event);
            }

            var textfieldActionButton = requireModule("Parent").byClass(target, classList.textfield.action_button);
            if (textfieldActionButton) {
                requireModule("TextfieldAdapter").handleActionButton(textfieldActionButton, domEvent.event);
                return;
            }

            var tab = requireModule("Parent").byClass(target, classList.tab.container);
            if (tab) {
                requireModule("TabAdapter").handleClickEvent(tab, domEvent.event);
                return;
            }
            
            var textfieldInput = requireModule("Parent").byClass(target, classList.textfield.input);
            if (textfieldInput !== null) {
                requireModule("TextfieldAdapter").handleClickEvent(target, domEvent.event);
            }
            
            var selectText = requireModule("Parent").byClass(target, classList.select.selected_text);
            if (selectText) {
                requireModule("SelectAdapter").handleClickEvent(selectText, domEvent.event);
                return;
            }
        }

        if (type == eventTypes.MOUSE.DBLCLICK) {

        }

        if (type == eventTypes.MOUSE.MOUSEUP) {
            // Handle tab scroll mouse up
            var scrollArea = requireModule("Parent").byAttribute(target, 'data-scroll-area');
            if (scrollArea) {
                handleTabScrollMouseUp(scrollArea, domEvent.event);
            }
        }

        if (type == eventTypes.MOUSE.MOUSEDOWN) {
            // Handle ripple effect
            var rippleElement = requireModule("Parent").byClass(target, classList.ripple.ripple);
            if (rippleElement && domEvent.event.button === 0) {
                requireModule("Ripple").handleEvent(domEvent.type, domEvent.event);
            }
            
            // Handle tab scroll mouse down
            var scrollArea = requireModule("Parent").byAttribute(target, 'data-scroll-area');
            if (scrollArea) {
                handleTabScrollMouseDown(scrollArea, domEvent.event);
            }
        }

        if (type == eventTypes.MOUSE.MOUSEMOVE) {
            // Handle tab scroll mouse move
            handleTabScrollMouseMove(domEvent.event);
        }

        if (type == eventTypes.MOUSE.MOUSEOVER) {

        }

        if (type == eventTypes.MOUSE.MOUSEOOUT) {

        }

        if (type == eventTypes.MOUSE.CONTEXTMENU) {

        }
    }

    function handleKeyboardEvent(event) {
        var domEvent = new(requireModule("DOMEvent"))(event);
        var target = domEvent.target;
        var type = domEvent.type;

        if (type == eventTypes.KEYBOARD.KEYDOWN) {

        }

        if (type == eventTypes.KEYBOARD.KEYUP) {

        }

        if (type == eventTypes.KEYBOARD.KEYPRESS) {

        }

        var textfieldInput = requireModule("Parent").byClass(target, classList.textfield.input);
        if (textfieldInput != null) {
            var textfield = requireModule("Parent").byClass(textfieldInput, classList.textfield.container);
            if (textfield != null) {
                requireModule("TextfieldAdapter").handleKeyboardEvent(target, domEvent.event);
            }
        }

        if (domEvent.event.key === "Escape") {
            var activeDialog = requireModule("components:state").ACTIVE_DIALOG;
            if (activeDialog && activeDialog.closeOnEscape) {
                activeDialog.close();
            }
            console.log("Escape key pressed");
        } else if (domEvent.event.key === "Enter") {
            console.log("Enter key pressed");
        }
    }

    function handleInput(event) {
        var target = event.target;

        var textfieldInput = requireModule("Parent").byClass(target, classList.textfield.input);
        if (textfieldInput !== null) {
            requireModule("TextfieldAdapter").handleInputEvent(target, event);
        }

        // Handle Top App Bar search input
        var topAppBarSearchInput = requireModule("Parent").byAttribute(target, 'data-search-input');
        if (topAppBarSearchInput) {
            requireModule("TopAppBarAdapter").handleSearchInput(topAppBarSearchInput, event);
        }
    }

    function handleTouchEvent(e) {
        var event = new(requireModule("TouchEvent"))(e);
        var target = e.target;

        requireModule("JSEventController").handleTouchEvent(event, function() {
            // Handle tab scroll touch events
            var scrollArea = requireModule("Parent").byAttribute(target, 'data-scroll-area');
            if (scrollArea) {
                if (e.type === eventTypes.TOUCH.TOUCHSTART) {
                    handleTabScrollTouchStart(scrollArea, e);
                } else if (e.type === eventTypes.TOUCH.TOUCHMOVE) {
                    handleTabScrollTouchMove(scrollArea, e);
                } else if (e.type === eventTypes.TOUCH.TOUCHEND) {
                    handleTabScrollTouchEnd(scrollArea, e);
                } else if (e.type === eventTypes.TOUCH.TOUCHCANCEL) {
                    handleTabScrollTouchCancel(scrollArea, e);
                }
            }

            // Other touch events
            event.onTap(function(touch) {
                console.log("Tap", touch);
            });

            event.onLongTap(function(touch) {
                console.log("Long press", touch);
            });

            event.onDragStart(function(touch) {
                console.log("Drag started", touch);
            });
            event.onDragMove(function(touch) {
                console.log("Dragging", touch);
            });
            event.onDrop(function(touch) {
                console.log("Dropped", touch);
            });
            event.onDragCancel(function(touch) {
                console.log("Drag cancelled", touch);
            });

            event.onSwipe(function(touch) {
                console.log("Swipe detected", touch.direction);
            });
            event.onSwipeLeft(function(touch) {
                console.log("Swipe Left", touch);
            });
            event.onSwipeRight(function(touch) {
                console.log("Swipe Right", touch);
            });
            event.onSwipeUp(function(touch) {
                console.log("Swipe Up", touch);
            });
            event.onSwipeDown(function(touch) {
                console.log("Swipe Down", touch);
            });

            event.onSwipeBottomToTop(function(touch) {
                console.log("Swipe Bottom → Top", touch);
            });
            event.onSwipeTopToBottom(function(touch) {
                console.log("Swipe Top → Bottom", touch);
            });

            event.initHandler();
        });
    }
    
    function handleWheelEvent(event) {
        var target = event.target;
        
        // Handle tab scroll wheel event
        var scrollArea = requireModule("Parent").byAttribute(target, 'data-scroll-area');
        if (scrollArea) {
            handleTabScrollWheel(scrollArea, event);
        }
    }

    // ===== TAB SCROLL HANDLERS =====
    
    /**
     * Handle touch start on tab scroll area
     */
    function handleTabScrollTouchStart(scrollArea, event) {
        var touch = event.touches[0];
        var scrollState = requireModule("TabScrollState");
        
        scrollState.setState(scrollArea, {
            isScrolling: true,
            startX: touch.pageX - scrollArea.offsetLeft,
            scrollLeft: scrollArea.scrollLeft,
            lastX: touch.pageX,
            lastTime: Date.now(),
            velocity: 0
        });
        
        scrollArea.style.scrollBehavior = 'auto';
    }
    
    /**
     * Handle touch move on tab scroll area
     */
    function handleTabScrollTouchMove(scrollArea, event) {
        var scrollState = requireModule("TabScrollState");
        var state = scrollState.getState(scrollArea);
        
        if (!state.isScrolling) return;
        
        event.preventDefault();
        
        var touch = event.touches[0];
        var currentTime = Date.now();
        
        var x = touch.pageX - scrollArea.offsetLeft;
        var walk = (x - state.startX) * 1.5; // Scroll speed multiplier
        
        scrollArea.scrollLeft = state.scrollLeft - walk;
        
        // Calculate velocity for momentum
        var timeDelta = currentTime - state.lastTime;
        if (timeDelta > 0) {
            var velocity = (touch.pageX - state.lastX) / timeDelta;
            scrollState.setState(scrollArea, {
                velocity: velocity,
                lastX: touch.pageX,
                lastTime: currentTime
            });
        }
    }

    /**
     * Handle touch end on tab scroll area
     */
    function handleTabScrollTouchEnd(scrollArea, event) {
        var scrollState = requireModule("TabScrollState");
        var state = scrollState.getState(scrollArea);
        
        if (!state.isScrolling) return;
        
        scrollState.setState(scrollArea, { isScrolling: false });
        
        // Apply momentum scrolling
        if (Math.abs(state.velocity) > 0.1) {
            requireModule("TabScrollAdapter").applyMomentum(scrollArea, state.velocity);
        }

        scrollArea.style.scrollBehavior = 'smooth';
    }

    /**
     * Handle touch cancel on tab scroll area
     */
    function handleTabScrollTouchCancel(scrollArea, event) {
        var scrollState = requireModule("TabScrollState");
        scrollState.resetState(scrollArea);
        scrollArea.style.scrollBehavior = 'smooth';
    }

    /**
     * Handle mouse down on tab scroll area
     */
    function handleTabScrollMouseDown(scrollArea, event) {
        event.preventDefault();

        var scrollState = requireModule("TabScrollState");

        scrollState.setState(scrollArea, {
            isScrolling: true,
            startX: event.pageX - scrollArea.offsetLeft,
            scrollLeft: scrollArea.scrollLeft
        });

        scrollArea.style.cursor = 'grabbing';
        scrollArea.style.scrollBehavior = 'auto';
    }

    /**
     * Handle mouse move (global)
     */
    function handleTabScrollMouseMove(event) {
        var scrollState = requireModule("TabScrollState");
        
        // Find all scroll areas that are currently scrolling
        document.querySelectorAll('[data-scroll-area]').forEach(function(scrollArea) {
            var state = scrollState.getState(scrollArea);
            
            if (!state.isScrolling) return;
            
            event.preventDefault();
            var x = event.pageX - scrollArea.offsetLeft;
            var walk = (x - state.startX) * 2;
            scrollArea.scrollLeft = state.scrollLeft - walk;
        });
    }

    /**
     * Handle mouse up (global)
     */
    function handleTabScrollMouseUp(scrollArea, event) {
        var scrollState = requireModule("TabScrollState");
        var state = scrollState.getState(scrollArea);
        
        if (!state.isScrolling) return;
        
        scrollState.setState(scrollArea, { isScrolling: false });
        scrollArea.style.cursor = 'grab';
        scrollArea.style.scrollBehavior = 'smooth';
    }

    /**
     * Handle wheel event on tab scroll area
     */
    function handleTabScrollWheel(scrollArea, event) {
        // Only handle horizontal scroll or shift+wheel
        if (Math.abs(event.deltaX) > Math.abs(event.deltaY) || event.shiftKey) {
            event.preventDefault();
            
            var delta = event.deltaX || event.deltaY;
            scrollArea.scrollLeft += delta;
        }
    }
    
    /**
     * Initialize all tab scrollers on page
     */
    function initializeAllTabScrollers() {
        var tabBars = document.querySelectorAll('[data-tab-scroller]');
        tabBars.forEach(function(tabBar) {
            var scrollArea = tabBar.querySelector('[data-scroll-area]');
            var scrollContent = tabBar.querySelector('[data-scroll-content]');
            
            if (scrollArea && scrollContent) {
                requireModule("TabScrollAdapter").checkScrollNeeded(scrollArea, scrollContent);
                
                // Set cursor for desktop
                scrollArea.style.cursor = 'grab';
            }
        });
    }

    function initializeAllTopAppBars() {
        var topAppBars = document.querySelectorAll('[data-top-app-bar]');
        topAppBars.forEach(function(topAppBar) {
            requireModule("TopAppBarAdapter").initScrollListener(topAppBar);
            requireModule("TopAppBarAdapter").initSearchListeners(topAppBar);
        });
    }
 
    /**
     * Handle window resize to recalculate scroll needs
     */
    function handleResizeEvent() {
        initializeAllTabScrollers();

         // Handle search bar resize
        var searchBars = document.querySelectorAll('[data-search-mode]');
        searchBars.forEach(function(topAppBar) {
            requireModule("TopAppBarAdapter").handleSearchResize();
        });
    }

    var eventHandler = {
        handleChange: handleChangeEvent,
        handleFocusEvent: handleFocusEvent,
        handleMouseEvent: handleMouseEvent,
        handleKeyboardEvent: handleKeyboardEvent,
        handleInput: handleInput,
        handleTouchEvent: handleTouchEvent,
        handleWheelEvent: handleWheelEvent,
        handleResizeEvent: handleResizeEvent
    };

    function addComponentsListeners() {
        var signal = requireModule("Event").addEventListener;
        signal(document, eventTypes.CHANGE             , eventHandler.handleChange);
        signal(document, eventTypes.FOCUS              , eventHandler.handleFocusEvent);
        signal(document, eventTypes.BLUR               , eventHandler.handleFocusEvent);
        signal(document, eventTypes.FOCUSIN            , eventHandler.handleFocusEvent);
        signal(document, eventTypes.FOCUSOUT           , eventHandler.handleFocusEvent);
        signal(document, eventTypes.MOUSE.CLICK        , eventHandler.handleMouseEvent);
        signal(document, eventTypes.MOUSE.DBLCLICK     , eventHandler.handleMouseEvent);
        signal(document, eventTypes.MOUSE.MOUSEDOWN    , eventHandler.handleMouseEvent);
        signal(document, eventTypes.MOUSE.MOUSEUP      , eventHandler.handleMouseEvent);
        signal(document, eventTypes.MOUSE.MOUSEMOVE    , eventHandler.handleMouseEvent);
        signal(document, eventTypes.MOUSE.CONTEXTMENU  , eventHandler.handleMouseEvent);
        signal(document, eventTypes.TOUCH.TOUCHSTART   , eventHandler.handleTouchEvent , false);
        signal(document, eventTypes.TOUCH.TOUCHMOVE    , eventHandler.handleTouchEvent , false);
        signal(document, eventTypes.TOUCH.TOUCHEND     , eventHandler.handleTouchEvent , false);
        signal(document, eventTypes.TOUCH.TOUCHCANCEL  , eventHandler.handleTouchEvent , false);
        signal(document, eventTypes.KEYBOARD.KEYUP     , eventHandler.handleKeyboardEvent, true);
        signal(document, eventTypes.KEYBOARD.KEYDOWN   , eventHandler.handleKeyboardEvent, true);
        signal(document, eventTypes.KEYBOARD.KEYPRESS  , eventHandler.handleKeyboardEvent, true);
        signal(document, 'input', eventHandler.handleInput, true);
        signal(document, 'wheel', eventHandler.handleWheelEvent, { passive: false });
        signal(window, 'resize', eventHandler.handleResizeEvent);
        
        // Initialize components on page load
        initializeAllTabScrollers();

        initializeAllTopAppBars();
    }

    addComponentsListeners();
}), true);