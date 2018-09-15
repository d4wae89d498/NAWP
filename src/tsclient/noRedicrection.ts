import * as $ from "jquery";
import {SocketClient} from "./socketClient";

/**
 * The noRedirection class
 */
export class NoRedirection {
    public SocketClient: SocketClient;
    public constructor() {
        this.SocketClient = new SocketClient();
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
                this.SocketClient.socket.emit("packet", {data: {name: "john"}, url: "/admin/login", clientVar: window["clientVar"], templates: shortTemplate});
                // logging it
                console.log($(e.target).attr("id"));
                console.log("form redirection canceled");
                console.log("form action : ");
                console.log(formAction);
                console.log("form data : ");
                console.log(formData);
                // todo : send a socketio call
            });
        // }
        console.log("form to ajax applied");
        return;
    }
}
