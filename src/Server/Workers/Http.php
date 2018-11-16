<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/5/2018
 * Time: 7:55 PM
 */

use App\Ipolitic\Nawpcore\Kernel;
use App\Ipolitic\Nawpcore\Exceptions\Exception;
use Workerman\Worker;
use Workerman\WebServer;
use Jasny\HttpMessage\ServerRequest;

/**
 * Class Http
 */
class Http
{
    /**
     * @var WebServer
     */
    public $worker;

    /**
     * Http constructor.
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    public function __construct()
    {
        require_once join(DIRECTORY_SEPARATOR, [__DIR__, "..", "..", "..","vendor", "autoload.php"]);
        $kernel = new Kernel();
        Worker::$eventLoopClass = $_ENV["EVENT_LOOP_CLASS"];
        $this->worker = new WebServer(
            "http://0.0.0.0:" .$_ENV["HTTP_WORKER_PORT"],
            [],
            function (Workerman\Connection\ConnectionInterface &$connection) use (&$kernel) {
                var_dump(\Workerman\Protocols\HttpCache::$header);
                try {
                    \App\Ipolitic\Nawpcore\Components\PacketAdapter::populateGet();
                    $request = (new ServerRequest())->withGlobalEnvironment(true);
                    $requestHandler = new \App\Ipolitic\Nawpcore\Components\RequestHandler(
                        $kernel,
                    isset($request->getServerParams()["REQUEST_METHOD"]) ? $request->getServerParams()["REQUEST_METHOD"] : "GET"
                    );
                    $dispatcher = (new \Ellipse\Dispatcher($requestHandler, $kernel->middlewareCollection->getArrayCopy()));
                    $response = $dispatcher->handle($request);
                    $connection->send((string) $response->getBody());
                } catch (\Exception $ex) {
                    $connection->send(
                        isset($_ENV["APP_DEBUG"]) && (((int) $_ENV["APP_DEBUG"]) === 1) ?
                            Exception::catch($ex)
                            :
                            "Our server is currently in maintenance mode. Please come back later."
                    );
                    throw $ex;
                }
            }
        );
        $this->worker->name = "http";
        $this->worker->count = $_ENV["HTTP_WORKER_CNT"];
        $this->worker->addRoot($_ENV["DOMAIN_NAME"], join(DIRECTORY_SEPARATOR, [__DIR__,"..","..","..","public"]));
        if (!defined("unix")) {
            Worker::runAll();
        }
    }
}
try {
    new Http();
} catch (\Psr\SimpleCache\InvalidArgumentException $ex) {
    echo 'Caught worker startup exception: ',  $e->getMessage(), PHP_EOL;
}
