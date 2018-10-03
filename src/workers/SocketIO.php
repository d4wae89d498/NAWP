<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/5/2018
 * Time: 7:55 PM
 */

use App\iPolitic\NawpCore\Kernel;
use App\iPolitic\NawpCore\Components\{ Packet, Utils };
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
        $viewLogger = new \App\iPolitic\NawpCore\Components\ViewLogger();
        $atlasInstance = &$kernel->atlas;
        $params = [&$viewLogger, []];
        Kernel::setKernel($kernel);
        $kernel->fillCollectionWithComponents($kernel->viewCollection, $params, 'views');
        $params = [&$atlasInstance];
        Kernel::setKernel($kernel);
        $kernel->fillCollectionWithComponents($kernel->controllerCollection, $params, 'controllers');
        Kernel::setKernel($kernel);
        $array = [];
        foreach($kernel->viewCollection as $k => $v) {
            $array[$k] = Utils::hideTwigIn(Utils::ocb(function() use($v) {
                    $v->twig();
            }));
        }
        $cli = new \App\iPolitic\NawpCore\components\Logger();
        //Worker::$eventLoopClass = '\Workerman\Events\Ev';
        $io = new \PHPSocketIO\SocketIO(8070);

        $io->on('connection', function (\PHPSocketIO\Socket $socket) use (&$kernel, $array, $cli) {
            echo "got connection" . PHP_EOL;
            $socket->on("packet", function (array $data) use (&$kernel, $socket, $array, $cli) {
                $cli->log("Got SOCKET Request", "info");
                echo "got packet" . PHP_EOL;
                foreach ($data["data"] as $key => $array) {
                   if(isset($array["name"]) && isset($array["value"])) {
                       $data["data"][$array["name"]] = $array["value"];
                       unset($data["data"][$key]);
                   }
                }


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
                        $packet,
                        $array
                    );
                    if(is_array($response)) {
                        $newResponse = [];
                        foreach ($response as $k => $v) {
                            if(is_array($v)) {
                                foreach ($v as $u => $w) {
                                    if(is_array($w)) {
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
                    echo "test";
                    echo PHP_EOL;
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
