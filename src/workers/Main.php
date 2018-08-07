<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/5/2018
 * Time: 7:55 PM
 */
namespace App\Workers;

use App\iPolitic\NawpCore\Kernel;
use Workerman\WebServer;
use Workerman\Worker;

class Main
{
    public $worker;
    public function __construct()
    {
        // needed lines for startup
        require_once join(DIRECTORY_SEPARATOR, [__DIR__, "..", "..", "vendor", "autoload.php"]);
        $kernel = new Kernel();
        Kernel::loadDir(join(DIRECTORY_SEPARATOR, [__DIR__, "..", "..", "src"]));
        Kernel::loadDir(join(DIRECTORY_SEPARATOR, [__DIR__, "..", "..", "bundles"]));
        $m = new \Memcached();
        $m->addServer('localhost', 11211);
        $m->set("KERNEL", $kernel);
        echo "Kernel set in RAM";
        //todo : set kernel in memcached here.
        //todo : set webserver in the http worker.
        $this->worker = new WebServer("http://0.0.0.0:4980", [], function(&$connection)use($kernel){
            $response = "";
            $kernel->handle($response, "http", $_SERVER["REQUEST_URI"]);
            $connection->send($response);
        });
        $this->worker->name = "http";
        $this->worker->count = 1;
        $this->worker->addRoot("127.0.0.1", join(DIRECTORY_SEPARATOR,[__DIR__,"..","..","public"]));
        Worker::runAll();
    }
}
// Instanciate the file class if it was launched using a terminal
isset($argv) && isset($argv[0]) ? new Main() : null;