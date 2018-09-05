// import * as io from './socket.io-client/socket.io';

export class SocketClient {
    /*
     * Instance of current socket
     */
    public socket;

    /**
     * The SocketClient constructor
     */
    public constructor() {
        this.socket = null; //io("http://127.0.0.1:8070");
        console.log("socket built");
        console.log("aaa");
    }
}