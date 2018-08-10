<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/5/2018
 * Time: 7:55 PM
 */

use App\iPolitic\NawpCore\Kernel;
use Workerman\ {Worker, WebServer};

class Http
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

        // workerman setup
        $this->worker = new WebServer("http://0.0.0.0:5616", [], function(&$connection)use(&$kernel){
            $response = "var response must be of type string";
            $kernel->handle($response, $_SERVER["REQUEST_METHOD"], $_SERVER["REQUEST_URI"]);
            $connection->send($response);
        });
        $this->worker->name = "http";
        $this->worker->count = 1;
        $this->worker->addRoot("127.0.0.1", join(DIRECTORY_SEPARATOR,[__DIR__,"..","..","public"]));
        Worker::runAll();
    }
}
new Http();