import {NoRedirection} from "../NoRedicrection";
const $: JQueryStatic  = window["$"];

export function listen(NoRedir: NoRedirection) {
    console.log("listening");
    $("[name=\"disableJavascriptBtn\"]").on("change", function() {
        console.log("changed");
        if ($(this).is(":checked")) {
                window["enableCLR"] = false;
                NoRedirection.setCookie("disableCLR", "true", 1);
                console.log("[CLR DISABLED]");
        } else {
            window["enableCLR"] = true;
            NoRedirection.setCookie("disableCLR", "false", 1);
            console.log("[CLR ENABLED]");
        }
    });

    $("[name=\"disableCookiesBtn\"]").on("change", function () {
        if ($(this).is(":checked")) {
            window["enableCookies"] = "false";
            NoRedirection.setCookie("disableCookie", "true", 1);
        } else {
            window["enableCookies"] = "true";
            NoRedirection.setCookie("disableCookie", "false", 1);
        }
    });
}