const $ = window["$"];
import {SocketClient} from "./socketClient";

/**
 * The noRedirection class
 */
export class NoRedirection {
    public SocketClient: SocketClient;
    public constructor() {
        this.SocketClient = new SocketClient(this);
        this.init();
    }

    public init(): void {
        this.applyForm();
    }

    /**
     * Will apply the no redirection rule to all the dom form tags
     */

    public applyForm(): void {
        // we retrive all forms
        // let formElements: any = $("form");
        // foreach forms
       // for (let formElement in formElements) {
            // when submitting formElement
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
                    shortTemplate[template] = templates[template];
                    if (typeof shortTemplate[template]["twig"] !== "undefined") {
                        delete shortTemplate[template]["twig"];
                    }
                }
                this.SocketClient.socket.emit("packet", {data: formData, url: "/admin/login", clientVar: window["clientVar"], templates: shortTemplate, cookies: document.cookie, http_referer: window.location.href});
            });
        // }
        // if we are on login / register page
        if (window.location.href.indexOf("admin/login") > -1) {
            // hiding by default registration area
            $("#registrationSection").slideToggle();
            // expect when switching
            $("[name=\"accessTypeRadio\"]").on("click",
                () => {
                    console.log("Button toggled");
                    $("#registrationSection").slideToggle();
                }
            );
        }
        return;
    }
}
