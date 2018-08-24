import * as $ from 'jquery';
import {SocketClient} from "./socketClient";

/**
 * The noRedirection class
 */
export class noRedirection {
    public SocketClient: SocketClient;
    public constructor() {
        this.SocketClient = new SocketClient();
    }

    /**
     * Will apply the no redirection rule to all the dom form tags
     */
    public applyForm(): void {
        // we retrive all forms
        let formElements: JQuery = $('form');
        // foreach forms
        for (let formElement in formElements) {
            // when submitting formElement
            $(formElement).on("submit",
                (e: Event) => {
                    // we prevent it from reloading
                    e.preventDefault();
                    let formAction: string = $(e.target).prop('action');
                    let formData: object = $(e.target).serializeArray();
                    this.SocketClient.socket.emit("packet", {data: {name: "john"}, url: "/hello"});
                    // logging it
                    console.log("form redirection canceled");
                    console.log("form action : ");
                    console.log(formAction);
                    console.log("form data : ");
                    console.log(formData);
                    // todo : send a socketio call
                });
        }
        return;
    }
}
