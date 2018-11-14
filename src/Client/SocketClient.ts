import * as io from "socket.io-client";
import * as morphdom from "morphdom";
const $ = window["$"];
import {ClientSideRendering} from "./ClientSideRendering";
import {NoRedirection} from "./NoRedicrection";
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
                if (typeof data["debugBar"] !== "undefined") {
                    morphdom($("[data-id=\"debugBar\"]")[0], $(data["debugBar"])[0]);
                    delete data["debugBar"];
                }
                console.log(data);
                if (data instanceof Object) {
                    ClientSideRendering.RenderStates(data);
                }
            });
        });
    }
}