import {JsonViewer} from "./DebugBar/jsonViewer";

const $: JQueryStatic  = window["$"];
import LoginForm from "./Elements/LoginForm";
import {SocketClient} from "./SocketClient";

/**
 * The noRedirection class
 */
export class NoRedirection {
    public SocketClient: SocketClient;
    public pages: object;
    public states: object;
    public constructor() {
        this.pages = [
            new LoginForm(),
        ];
        this.SocketClient = new SocketClient(this);
        this.init();
    }

    public init(states: object = {}): void {
        this.states = states;
        this.applyForm();
    }
    static setCookie(name, value, days) {
        let expires = "";
        if (days) {
            let date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }
    static getCookie(name) {
        let nameEQ = name + "=";
        let ca = document.cookie.split(";");
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === " ") c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }
    static eraseCookie(name) {
        document.cookie = name + "=; Max-Age=-99999999;";
    }
    /**
     * Will apply the no redirection rule to all the dom form tags
     * Called at each page init // refresh
     */

    public applyForm(): void {
            $("form").on("submit",
                (e: Event) => {
                    if (window["enableCLR"]) {
                        // we prevent it from reloading
                        e.preventDefault();
                        e.stopImmediatePropagation();
                        let formAction: string = $(e.target).prop("action");
                        let formData: object = $(e.target).serializeArray();
                        let templates: object = window["templates"];
                        let shortTemplate = {};
                        for (let template in templates) {
                            if (templates.hasOwnProperty(template)) {
                                shortTemplate[template] = templates[template];
                                if (typeof shortTemplate[template]["twig"] !== "undefined") {
                                    delete shortTemplate[template]["twig"];
                                }
                            }
                        }
                        this.SocketClient.socket.emit("packet", {data: formData, url: "/admin/login", clientVar: window["clientVar"], templates: shortTemplate, cookies: document.cookie, http_referer: window.location.href});
                    } else {
                        NoRedirection.setCookie("disableCLR", "true", 999);
                    }
            });
            for (let page in this.pages) {
                if (this.pages.hasOwnProperty(page)) {
                    this.pages[page].refreshTick();
                }
            }
            let cookie = NoRedirection.getCookie("disableCLR");
            let cookies = NoRedirection.getCookie("disableCookie");
            if ((cookie === null) || (cookie === "false")) {
                $("[name=\"disableJavascriptBtn\"]").prop("checked", false);
                window["enableCLR"] = true;
            } else {
                window["enableCLR"] = false;
                $("[name=\"disableJavascriptBtn\"]").prop("checked", true);
            }

            if ((cookies == null) || (cookies === "false")) {
                window["enableCookies"] = "true";
                $("[name=\"disableCookiesBtn\"]").prop("checked", false);
            } else {
                window["enableCookies"] = "false";
                $("[name=\"disableCookiesBtn\"]").prop("checked", true);
            }
        return;
    }
}


export interface IElement {
    refreshTick(): void;
}
