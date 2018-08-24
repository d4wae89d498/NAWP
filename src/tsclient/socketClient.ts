import * as io from 'socket.io-client';

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
        console.log("socketcli built")
    }
}
