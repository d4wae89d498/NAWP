import * as io from "socket.io-client";

export class SocketClient {
    /*
     * Instance of current socket
     */
    public socket;

    /**
     * The SocketClient constructor
     */
    public constructor() {
        this.socket = io("http://127.0.0.1:8070");
        this.socket.on("packetout", function(data) {
            alert("test");
            console.log("got packet");
            console.log(data);
        });
        console.log("socket built");
    }
}