<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/5/2018
 * Time: 7:55 PM
 */

use App\iPolitic\NawpCore\Kernel;
use Workerman\ {Worker};
use App\iPolitic\NawpCore\Components\Hsptp;

class SocketIO
{
    public $worker;
    public function __construct()
    {
        // needed lines for startup
        require_once join(DIRECTORY_SEPARATOR, [__DIR__, "..", "..", "vendor", "autoload.php"]);
        $kernel = new Kernel();
        Kernel::loadDir(join(DIRECTORY_SEPARATOR, [__DIR__, "..", "..", "src"]));
        Kernel::loadDir(join(DIRECTORY_SEPARATOR, [__DIR__, "..", "..", "bundles"]));

        $hsptp = new Hsptp();
        $img = $hsptp->encrypt("HELLO WORLD", 1);
        var_dump($img);
        $r = $hsptp->decrypt($img);
        var_dump($r);
        /**
         * Used for logging views
         */
        $viewLogger = new \App\iPolitic\NawpCore\Components\ViewLogger();
        /**
         * Used for creating controllers instance
         */
        $atlasInstance = &$kernel->atlas;
        $params = [&$viewLogger, []];
        $kernel->fillCollectionWithComponents($kernel->viewCollection, $params, 'views');
        $params = [&$atlasInstance];
        $kernel->fillCollectionWithComponents($kernel->controllerCollection, $params, 'controllers');
        var_dump($kernel->viewCollection);
        $this->worker = new \PHPSocketIO\SocketIO(8070);
        $this->worker->on('connection', function ($socket) use (&$kernel) {
            echo "got connection" . PHP_EOL;
            $socket->on('packet', function ($data) use (&$kernel) {
                echo "got packet : " . PHP_EOL;
                var_dump($data);
                $response = "";
                $kernel->handle($response, "SOCKET", $data);
            });
        });
        Worker::runAll();
    }
}
new SocketIO();