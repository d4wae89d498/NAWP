<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/5/2018
 * Time: 7:55 PM
 */

use App\iPolitic\NawpCore\Kernel;
use App\iPolitic\NawpCore\Components\Utils;
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

        $io->on('connection', function ($socket) use (&$kernel) {
            echo "got connection" . PHP_EOL;
            $socket->on("packet", function ($data) use (&$kernel, $socket) {
                echo "got packet" . PHP_EOL;
                /**
                 * @var $socket \PHPSocketIO\Socket
                 */
                $response = "";
                $obj = (new Packet($data, true))
                    ->useAdaptor()
                    ->toArray();
                $kernel->handle
                (
                    $response,
                    "SOCKET",
                    $obj,
                    false
                );
                $socket->emit("packetout", $_SERVER["REQUEST_URI"]);
                echo PHP_EOL;
            });
        });

        Worker::runAll();
    }
}
new SocketIO();