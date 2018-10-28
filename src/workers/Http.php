<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/5/2018
 * Time: 7:55 PM
 */

use App\iPolitic\NawpCore\Kernel;
use App\iPolitic\NawpCore\Components\Exception;
use Workerman\Worker;
use Workerman\WebServer;
use Jasny\HttpMessage\ServerRequest;

class Http
{
    public $worker;

    /**
     * Http constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        // needed lines for startup
        require_once join(DIRECTORY_SEPARATOR, [__DIR__, "..", "..", "vendor", "autoload.php"]);
        $kernel = new Kernel();

        $this->worker = new WebServer(
            "http://0.0.0.0:5616",
            [],
            function (Workerman\Connection\ConnectionInterface &$connection) use (&$kernel) {
                $response = "";
                try {
                    \App\iPolitic\NawpCore\Components\PacketAdapter::populateGet();
                    $request = (new ServerRequest())->withGlobalEnvironment(true);
                    $kernel->handle(
                        $response,
                        $request,
                        isset($_SERVER["REQUEST_METHOD"]) ?
                        $_SERVER["REQUEST_METHOD"] : "GET",
                        null,
                        $kernel->rawTwig
                    );
                    $connection->send($response);
                } catch (\Exception $exception) {
                    $connection->send(
                        isset($_ENV["APP_DEBUG"]) && (((int) $_ENV["APP_DEBUG"]) === 1) ?
                            Exception::catch($exception)
                            :
                            "Our server is currently in maintenance mode. Please come back later."
                    );
                    throw $exception;
                }
            }
        );
        $this->worker->name = "http";
        $this->worker->count = 1;
        $this->worker->addRoot("127.0.0.1", join(DIRECTORY_SEPARATOR, [__DIR__,"..","..","public"]));
        Worker::runAll();
    }
}
try {
    new Http();
} catch (\Exception $e) {
    echo 'Caught worker startup exception: ',  $e->getMessage(), PHP_EOL;
}
