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
        /**
         * Used for logging views
         */
        $viewLogger = new \App\iPolitic\NawpCore\Components\ViewLogger();
        /**
         * Used for creating controllers instance
         */
        $atlasInstance = &$kernel->atlas;
        $params = [&$viewLogger, []];
        Kernel::setKernel($kernel);
        $kernel->fillCollectionWithComponents($kernel->viewCollection, $params, 'views');
        $params = [&$atlasInstance];
        Kernel::setKernel($kernel);
        $kernel->fillCollectionWithComponents($kernel->controllerCollection, $params, 'controllers');
        Kernel::setKernel($kernel);
       // echo "kernel instance : " . PHP_EOL;
       // var_dump($kernel);
        /*
        $atlas = $kernel->getAtlas();
        $categoryRecord = $atlas->fetchRecord(\App\DataSources\User\UserMapper::CLASS, '2');
        var_dump($categoryRecord);
        */

        // workerman setup
        $this->worker = new WebServer("http://0.0.0.0:5616", [], function(&$connection)use(&$kernel){
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