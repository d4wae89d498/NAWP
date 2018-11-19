<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/5/2018
 * Time: 7:55 PM
 */

use App\Ipolitic\Nawpcore\Kernel;
use App\Ipolitic\Nawpcore\Components\Packet;
use Jasny\HttpMessage\ServerRequest;
use Workerman\Worker;

class SocketIO
{
    /**
     * @var Worker
     */
    public $worker;

    /**
     * SocketIO constructor.
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function __construct()
    {
        require_once join(DIRECTORY_SEPARATOR, [__DIR__, "..", "..", "..", "vendor", "autoload.php"]);
        Worker::$eventLoopClass = $_ENV["EVENT_LOOP_CLASS"];
        $io = new \PHPSocketIO\SocketIO($_ENV["SOCKETIO_WORKER_PORT"]);
        $kernel = new Kernel();
        $io->on('connection', function ($socket) use (&$kernel) {
            $socket->addedUser = false;
            $kernel->logger->info("Got connection");
            try {
                $socket->on("packet", function (array $data) use (&$kernel, $socket) {
                    $kernel->logger->log("Got SOCKET Request", "info");
                    try {
                        /**
                         * @var $socket \PHPSocketIO\Socket
                         */
                        $request = (new ServerRequest())->withGlobalEnvironment(true);
                        $packet = (new Packet($kernel, $request, $data, true))
                            ->useAdaptor()
                            ->toArray();
                        $response = new \Jasny\HttpMessage\Response();
                        $requestHandler = new \App\Ipolitic\Nawpcore\Components\RequestHandler(
                            $kernel,
                            "SOCKET",
                            $packet
                        );
                        $dispatcher = new \Ellipse\Dispatcher($requestHandler, $kernel->middlewareCollection->getArrayCopy());
                        $requestHandler->response = $dispatcher->handle($request);
                        $bodyStr =  (string)$requestHandler->response->getBody();
                        $socket->emit("packetout", $bodyStr);
                        return;
                    } catch (Exception $ex) {
                        echo "error" . PHP_EOL;
                        var_dump($ex->getMessage());
                        $socket->emit("packetout", "ERROR : " . $ex->getMessage());
                        throw new $ex;
                    }
                });
            } catch (Exception $ex) {
                echo "error" . PHP_EOL;
                var_dump($ex->getMessage());
                $socket->emit("packetout", "ERROR : " . $ex->getMessage());
                throw new $ex;
            }
        });
        $this->worker = &$io->worker;
        $this->worker->name = "socketio";
        $this->worker->count = 1; // $_ENV["SOCKETIO_WORKER_CNT"];
        if (!defined("unix")) {
            Worker::runAll();
        }
    }
}
try {
    new SocketIO();
} catch (Exception $ex) {
    echo 'Caught worker startup exception: ',  $ex->getMessage(), PHP_EOL;
}
