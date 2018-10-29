const $ = window["$"];
import LoginForm from "./elements2/loginForm";
import {SocketClient} from "./socketClient";

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

    /**
     * Will apply the no redirection rule to all the dom form tags
     * Called at each page init // refresh
     */

    public applyForm(): void {
        $("form").on("submit",
            (e: Event) => {
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
            });

        for (let page in this.pages) {
            if (this.pages.hasOwnProperty(page)) {
                this.pages[page].refreshTick();
            }
        }
        return;
    }
}


export interface IElement {
    refreshTick(): void;
}
