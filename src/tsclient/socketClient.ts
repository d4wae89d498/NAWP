import * as io from "socket.io-client";
const $ = window["$"];
import {ClientSideRendering} from "./clientSideRendering";
import {NoRedirection} from "./noRedicrection";
export class SocketClient {
    /*
     * Instance of current socket
     */
    public socket;

    /**
     * The SocketClient constructor
     */
    public constructor(noRedir: NoRedirection) {
        new ClientSideRendering(noRedir);
        $( document ).ready(() => {
            for (let key in window["baseTemplates"]) {
                if (window["baseTemplates"].hasOwnProperty(key)) {
                    window["baseTemplates"][key]["twig"] = ClientSideRendering.showTwigIn(
                        window["baseTemplates"][key]["twig"]
                    );
                }
            }
            this.socket = io("http://127.0.0.1:8070");
            this.socket.on("packetout", function(data) {
                data = JSON.parse(data);
                window["csr"] = ClientSideRendering;
                if (data instanceof Object) {
                    ClientSideRendering.RenderStates(data);
                }
            });
        });
    }
}