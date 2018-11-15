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
                let ndata = JSON.parse(data);
                window["csr"] = ClientSideRendering;
                if (typeof ndata["debugBar"] !== "undefined") {
                    morphdom($("[data-id=\"debugBar\"]")[0], $(ndata["debugBar"])[0]);
                    delete ndata["debugBar"];
                    $(document).ready(function() {
                        $("pre code").each(function(i, block) {
                            window["hljs"].highlightBlock(block);
                        });
                    });
                }
                if (ndata instanceof Object) {
                    ClientSideRendering.RenderStates(ndata);
                }
            });
        });
    }
}