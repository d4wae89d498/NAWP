import { NoRedirection } from "./NoRedicrection";
import { init } from "./SbAdmin";
import {JsonViewer} from "./DebugBar/jsonViewer";
import {listen} from "./DebugBar/Buttons";


$(document).ready(() => {
    init();
    window["enableCLR"] = ((NoRedirection.getCookie("diableCLR") === null) || (NoRedirection.getCookie("diableCLR") === "false"))
        ? "false" : "true";
    window["enableCookies"] = ((NoRedirection.getCookie("disableCookie") === null) || (NoRedirection.getCookie("disableCookie") === "false"))
    const noRedirectionInstance: NoRedirection = new NoRedirection();
    listen(noRedirectionInstance);
    setTimeout(() => { JsonViewer.refresh(); }, 0 );
});