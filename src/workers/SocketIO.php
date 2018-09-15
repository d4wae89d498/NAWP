<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/5/2018
 * Time: 7:55 PM
 */

use App\iPolitic\NawpCore\Kernel;
use App\iPolitic\NawpCore\Components\Packet;
use Workerman\ {Worker};

class SocketIO
{
    public $worker;
    public function __construct()
    {
        require_once join(DIRECTORY_SEPARATOR, [__DIR__, "..", "..", "vendor", "autoload.php"]);

        $kernel = new Kernel();
        Kernel::loadDir(join(DIRECTORY_SEPARATOR, [__DIR__, "..", "..", "src"]));
        Kernel::loadDir(join(DIRECTORY_SEPARATOR, [__DIR__, "..", "..", "bundles"]));

        $atlasInstance = &$kernel->atlas;
        Kernel::setKernel($kernel);

        $viewLogger = new \App\iPolitic\NawpCore\Components\ViewLogger();
        $params = [&$viewLogger, []];
        $kernel->fillCollectionWithComponents($kernel->viewCollection, $params, 'views');
        $params = [&$atlasInstance];
        Kernel::setKernel($kernel);

        $kernel->fillCollectionWithComponents($kernel->controllerCollection, $params, 'controllers');
        Kernel::setKernel($kernel);

        //Worker::$eventLoopClass = '\Workerman\Events\Ev';
        $io = new \PHPSocketIO\SocketIO(8070);

        $io->on('connection', function (\PHPSocketIO\Socket $socket) use (&$kernel) {
            echo "got connection" . PHP_EOL;
            $socket->on("packet", function (array $data) use (&$kernel, $socket) {
                echo "got packet" . PHP_EOL;
                try {
                    /**
                     * @var $socket \PHPSocketIO\Socket
                     */
                    $response = "";
                    $packet = (new Packet($data, true))
                        ->useAdaptor()
                        ->toArray();
                    $kernel->handle
                    (
                        $response,
                        "SOCKET",
                        $_SERVER["REQUEST_URI"],
                        $packet
                    );
                    if(is_array($response)) {
                        $newResponse = [];
                        foreach ($response as $k => $v) {
                            if(is_array($v)) {
                                foreach ($v as $u => $w) {
                                    if(is_array($w)) {
                                        foreach ($w as $a => $b) {
                                            if (strpos($a, "html_") === false) {
                                                $newResponse[$k][$u][$a] = $b;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $response = $newResponse;
                    }
                    $socket->emit("packetout", $response);
                    echo "test";
                    echo PHP_EOL;
                    return;
                } catch (Exception $ex) {
                    $socket->emit("packetout", "ERROR");
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
