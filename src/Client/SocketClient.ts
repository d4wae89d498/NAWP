import * as io from "socket.io-client";
import * as morphdom from "morphdom";
const $ = window["$"];
import {ClientSideRendering} from "./ClientSideRendering";
import {NoRedirection} from "./NoRedicrection";
import {JsonViewer} from "./DebugBar/jsonViewer";
window["jsonviewer"] = JsonViewer;
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
                if (typeof ndata["error"] !== "undefined") {
                    let html = ndata["error"];
                    let body = $("body");
                    body.html("<iframe frameborder=\"0\" id=\"someWhat\"></iframe><style> body { margin: 0; " +
                        "/* Reset default margin */ } " +
                        "iframe { display: block; " +
                        "/* iframes are inline by default */" +
                        " background: #000; border: none; /* Reset default border */ height: 100vh; " +
                        "/* Viewport-relative units */ width: 100vw; }");
                    let ifrm: any = document.getElementById("someWhat");
                    ifrm = ifrm.contentWindow || ifrm.contentDocument.document || ifrm.contentDocument;
                    ifrm.document.open();
                    ifrm.document.write(html);
                    ifrm.document.close();
                    console.log(ndata);
                    document.title = "NAWP socket server exception!";
                    return;
                }
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
                (async () => {
                    if (ndata instanceof Object) {
                        let str = await ClientSideRendering.RenderStates(ndata);
                    }
                    JsonViewer.refresh();
                })();
            });
        });
    }
}