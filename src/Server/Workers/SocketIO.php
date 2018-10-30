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
        $kernel = new Kernel();
        Worker::$eventLoopClass = $_ENV["EVENT_LOOP_CLASS"];
        $io = new \PHPSocketIO\SocketIO($_ENV["SOCKETIO_WORKER_PORT"]);

        $io->on('connection', function (\PHPSocketIO\Socket $socket) use (&$kernel) {
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
                    $response = (new \App\Ipolitic\Nawpcore\Components\RequestHandler($kernel, "SOCKET", $packet))->handle($request);
                   // var_dump((string) $response->getBody());
                    $socket->emit("packetout", (string) $response->getBody());
                    return;
                } catch (Exception $ex) {
                    while (@ob_end_flush());
                    echo "error" . PHP_EOL;
                    var_dump($ex->getMessage());
                    $socket->emit("packetout", "ERROR : " . $ex->getMessage());
                    throw new $ex;
                }
            });
        });
        $this->worker = $io->worker;
        $this->worker->name = "socketio";
        $this->worker->count = $_ENV["SOCKETIO_WORKER_CNT"];

        Worker::runAll();
    }
}
try {
    new SocketIO();
} catch (Exception $exception) {
    echo 'Caught worker startup exception: ',  $e->getMessage(), PHP_EOL;
}
