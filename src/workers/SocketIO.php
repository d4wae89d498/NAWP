<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/5/2018
 * Time: 7:55 PM
 */

use App\iPolitic\NawpCore\Kernel;
use Workerman\ {Worker, WebServer};

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
        $kernel->instantiateControllers();

        $this->worker = new \PHPSocketIO\SocketIO(8070);
        $this->worker->on('connection', function ($socket) use (&$kernel) {
            echo "got connection" . PHP_EOL;
            $this->worker->on('packet', function ($data) use ($socket, &$kernel) {
                echo "got packet" . PHP_EOL;
                $kernel->handle($response, "SOCKET", $data);
            });
        });
        Worker::runAll();
    }
}
new SocketIO();