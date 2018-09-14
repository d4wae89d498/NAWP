<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/5/2018
 * Time: 7:55 PM
 */

use App\iPolitic\NawpCore\Kernel;
use App\iPolitic\NawpCore\Components\Exception;
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

        /*
        $atlas = $kernel->getAtlas();
        $categoryRecord = $atlas->fetchRecord(\App\DataSources\User\UserMapper::CLASS, '2');
        var_dump($categoryRecord);
        */
        // workerman setup

        $this->worker = new WebServer(
            "http://0.0.0.0:5616",
            [],
            function(Workerman\Connection\ConnectionInterface &$connection)use(&$kernel) {
                try {
                    $kernel->handle($response, $_SERVER["REQUEST_METHOD"], $_SERVER["REQUEST_URI"]);
                } catch (\Exception $exception) {
                    $connection->send(
                        isset($_ENV["APP_DEBUG"]) && (((int) $_ENV["APP_DEBUG"]) === 1) ?
                            Exception::catch($exception)
                            :
                            "Our server is currently in maintenance mode. Please come back later."
                    );
                    throw $exception;
                }
            });
        $this->worker->name = "http";
        $this->worker->count = 1;
        $this->worker->addRoot("127.0.0.1", join(DIRECTORY_SEPARATOR,[__DIR__,"..","..","public"]));
        Worker::runAll();
    }
}
try {
    new Http();
} catch (\Exception $exception) {
    echo 'Caught worker startup exception: ',  $e->getMessage(), PHP_EOL;
}
