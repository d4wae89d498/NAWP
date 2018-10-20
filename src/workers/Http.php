<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/5/2018
 * Time: 7:55 PM
 */

use App\iPolitic\NawpCore\Kernel;
use App\iPolitic\NawpCore\Components\Exception;
use App\iPolitic\NawpCore\Components\Utils;
use Workerman\Worker;
use Workerman\WebServer;

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
        $params = [&$viewLogger, $kernel->logger, []];
        Kernel::setKernel($kernel);
        $kernel->fillCollectionWithComponents($kernel->viewCollection, $params, 'views');
        $params = [&$atlasInstance, $kernel->logger];
        Kernel::setKernel($kernel);
        $kernel->fillCollectionWithComponents($kernel->controllerCollection, $params, 'controllers');
        Kernel::setKernel($kernel);
        $array = [];
        foreach ($kernel->viewCollection as $k => $v) {
            $array[$k] = Utils::HideTwigIn(Utils::ocb(function () use ($v) {
                $v->twig();
            }));
        }


        $cli = new \App\iPolitic\NawpCore\components\Logger();
        echo "Colors are supported: " . ($cli->isSupported() ? 'Yes' : 'No') . "\n";
        echo "256 colors are supported: " . ($cli->are256ColorsSupported() ? 'Yes' : 'No') . "\n\n";
        if ($cli->isSupported()) {
            foreach ($cli->getPossibleStyles() as $style) {
                echo $cli->applyStyles($style, $style, "underline") . "\n";
            }
        }
        echo "\n";
        if ($cli->are256ColorsSupported()) {
            echo "Foreground colors:\n";
            for ($i = 1; $i <= 255; $i++) {
                echo $cli->applyStyles(str_pad(strval($i), 6, ' ', STR_PAD_BOTH), "color_$i");
                if ($i % 15 === 0) {
                    echo "\n";
                }
            }
            echo "\nBackground colors:\n";
            for ($i = 1; $i <= 255; $i++) {
                echo $cli->applyStyles(str_pad(strval($i), 6, ' ', STR_PAD_BOTH), "bg_color_$i");
                if ($i % 15 === 0) {
                    echo "\n";
                }
            }
            echo "\n";
        }

        echo $cli->title("some title");//, function(){return true;});
        echo $cli->desc("some desc");//, function(){return true;});
        echo $cli->list("some desc", "0001", "0002", "0003");//, function(){return true;});
        echo $cli->logWithStyle("some info", "underline", "title");//, function(){return true;});
        $cli->check("anno func", function ():bool {
            sleep(2);
            return false;
        });
        /*
        $atlas = $kernel->getAtlas();

        $categoryRecord = $atlas->fetchRecord(\App\DataSources\User\User::CLASS, '2');
        var_dump($categoryRecord);
        */
        // workerman setup

        $this->worker = new WebServer(
            "http://0.0.0.0:5616",
            [],
            function (Workerman\Connection\ConnectionInterface &$connection) use (&$kernel, &$array, $cli) {
                $cli->info("Got HTTP Request ");
                $response = "";
                try {
                    $kernel->handle(
                        $response,
                        isset($_SERVER["REQUEST_METHOD"]) ?
                        $_SERVER["REQUEST_METHOD"] : "GET",
                        $_SERVER["REQUEST_URI"],
                        null,
                        $array
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
