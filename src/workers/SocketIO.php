<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/5/2018
 * Time: 7:55 PM
 */

use App\iPolitic\NawpCore\Kernel;
use App\iPolitic\NawpCore\Components\Packet;
use Jasny\HttpMessage\ServerRequest;
use Workerman\ {Worker};

class SocketIO
{
    public $worker;
    public function __construct()
    {
        require_once join(DIRECTORY_SEPARATOR, [__DIR__, "..", "..", "vendor", "autoload.php"]);

        $kernel = new Kernel();
        //Worker::$eventLoopClass = '\Workerman\Events\Ev';
        $io = new \PHPSocketIO\SocketIO(8070);

        $io->on('connection', function (\PHPSocketIO\Socket $socket) use (&$kernel) {
            $socket->on("packet", function (array $data) use (&$kernel, $socket) {
                $kernel->logger->log("Got SOCKET Request", "info");
                try {
                    /**
                     * @var $socket \PHPSocketIO\Socket
                     */
                    $response = "";
                    $request = (new ServerRequest())->withGlobalEnvironment(true);
                    $packet = (new Packet($kernel, $request, $data, true))
                        ->useAdaptor()
                        ->toArray();

                    $kernel->handle(
                        $response,
                        $request,
                        "SOCKET",
                        $packet,
                        $kernel->rawTwig
                    );
                    if (is_array($response)) {
                        $newResponse = [];
                        foreach ($response as $k => $v) {
                            if (is_array($v)) {
                                foreach ($v as $u => $w) {
                                    if (is_array($w)) {
                                        foreach ($w as $a => $b) {
                                            $newResponse[$k][$u][$a] = $b;
                                        }
                                    }
                                }
                            }
                        }
                        $response = $newResponse;
                    }
                    $socket->emit("packetout", $response);
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
        Worker::runAll();
    }
}
try {
    new SocketIO();
} catch (Exception $exception) {
    echo 'Caught worker startup exception: ',  $e->getMessage(), PHP_EOL;
}
