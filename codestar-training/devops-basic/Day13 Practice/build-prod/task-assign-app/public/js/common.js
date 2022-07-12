function ready(callbackFunc) {
    if (document.readyState !== "loading") {
        // Document is already ready, call the callback directly
        callbackFunc();
    } else if (document.addEventListener) {
        // All modern browsers to register DOMContentLoaded
        document.addEventListener("DOMContentLoaded", callbackFunc);
    } else {
        // Old IE browsers
        document.attachEvent("onreadystatechange", function () {
            if (document.readyState === "complete") {
                callbackFunc();
            }
        });
    }
}

/**
 * Get value of a selector
 *
 * Shorthand for: document.querySelector(selector).value
 * @param {*} selector
 */
const getValueSelector = selector => document.querySelector(selector).value;

/**
 * Set value of a selector
 *
 * @param {*} selector 
 * @param {*} value 
 */
const setValue = (selector, value) => {
    $(selector).val(value)
}

const setCloseTimeout = (modalSelector, timeout) => {
    $(modalSelector).modal("show").on("shown.bs.modal", function () {
        window.setTimeout(function () {
            $(modalSelector).modal("hide");
        }, timeout);
    });
}