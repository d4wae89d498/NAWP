<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/5/2018
 * Time: 7:47 PM
 */
namespace App\iPolitic\NawpCore;

use App\iPolitic\NawpCore\Collections\ControllerCollection;
use App\iPolitic\NawpCore\Collections\ViewCollection;
use App\iPolitic\NawpCore\Components\Collection;
use App\iPolitic\NawpCore\components\Logger;
use App\iPolitic\NawpCore\components\Packet;
use Jasny\HttpMessage\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Simple\FilesystemCache;
use App\iPolitic\NawpCore\Components\Utils;
use App\iPolitic\NawpCore\Components\ViewLogger;
use Atlas\Orm\Atlas;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Dotenv\Dotenv;

class Kernel implements LoggerAwareInterface
{
    public const CACHE_FOLDER_NAME = "cache";
    /**
     * @var LoggerInterface
     */
    public $logger;
    /**
     * @var string $cachePath
     */
    public $cachePath = "";
    /**
     * @var ControllerCollection $controllerCollection
     */
    public $controllerCollection;
    /**
     * @var ViewCollection $viewCollection
     */
    public $viewCollection;
    /**
     * @var Atlas
     */
    public $atlas;
    /**
     * @var CacheInterface
     */
    public $sessionCache;
    /**
     * @var CacheInterface
     */
    public $packetAdapterCache;
    /**
     * @var array
     */
    public $rawTwig = [];

    /**
     * Kernel constructor.
     */
    public function __construct()
    {
        Kernel::loadDir(join(DIRECTORY_SEPARATOR, [__DIR__, "..", "..", "..", "src"]));
        Kernel::loadDir(join(DIRECTORY_SEPARATOR, [__DIR__, "..", "..", "..", "bundles"]));
        $dotEnv = new Dotenv();
        $dotEnv->load(join(DIRECTORY_SEPARATOR, [__DIR__, "..", "..", "..", "configs", ".env"]));
        $this->init();
    }

    /**
     * Wil recursivly require_once all files in the given directory
     * @param string $directory
     */
    public static function loadDir(string $directory): void
    {
        if (is_dir($directory)) {
            $scan = scandir($directory);
            unset($scan[0], $scan[1]); //unset . and ..
            foreach ($scan as $file) {
                if (is_dir($directory."/".$file)) {
                    self::loadDir($directory."/".$file);
                } else {
                    if (!file_exists($directory."/.noInclude")) {
                        if (strpos($file, '.php') !== false) {
                            require_once($directory."/".$file);
                        }
                    }
                }
            }
        }
    }

    /**
     *  Will handle a request
     * @param string $response
     * @param string $requestType
     * @param ServerRequestInterface $request
     * @param Packet|null $packet
     * @param array $array
     * @param ViewLogger|null $viewLogger
     * @throws \iPolitic\Solex\RouterException
     * @throws \Exception
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function handle(&$response, ServerRequestInterface &$request, string $requestType, $packet = null, $array = [], &$viewLogger = null): void
    {
        $this->controllerCollection->handle($this, $response, $request, $requestType, $packet, $array, $viewLogger);
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function init(): void
    {
        $this->setLogger(new Logger());
        // set memory to 4go
        ini_set('memory_limit', '2048M');
        $this->cachePath = join(DIRECTORY_SEPARATOR, [__DIR__, "..", "..", "..", self::CACHE_FOLDER_NAME]);
        $this->controllerCollection = new ControllerCollection();
        $this->controllerCollection->setLogger($this->logger);
        $this->viewCollection = new ViewCollection();
        $this->viewCollection->setLogger($this->logger);
        $this->atlas = $this->getAtlas();
        $this->packetAdapterCache = new FilesystemCache('', 0, join(DIRECTORY_SEPARATOR, [$this->cachePath, "packetAdapter"]));
        $this->sessionCache = new FilesystemCache('', 0, join(DIRECTORY_SEPARATOR, [$this->cachePath, "session"]));

        /**
         * Used for logging views
         */
        $rq = new ServerRequest();
        $viewLogger = new Components\ViewLogger($this, $rq);
        /**
         * Used for creating controllers instance
         */
        $atlasInstance = &$this->atlas;
        $params = [&$viewLogger, $this->logger, []];
        $this->fillCollectionWithComponents($this->viewCollection, $params, 'views');
        $params = [&$atlasInstance, $this->logger];
        $this->fillCollectionWithComponents($this->controllerCollection, $params, 'controllers');
        foreach ($this->viewCollection as $k => $v) {
            $this->rawTwig[$k] = Utils::HideTwigIn(Utils::ocb(function () use ($v) {
                $v->twig();
            }));
        }
    }

    /**
     * Will instantiate all components declared in a "$components" folder following PSR standars
     * @param Collection $collection
     * @param array $arguments
     * @param string $componentName
     */
    public function fillCollectionWithComponents(Collection &$collection, array &$arguments = [], string $componentName = ""): void
    {
        // foreach controllers
        array_map(
            function ($component) use (&$collection) {
                /**
                 * @var mixed $component the component instance that will be added to the collection
                 */
                $collection->append($component);
            },
            (
            // remove null values
            array_filter(
                // convert declared class name to controller instance if match, or null value
                array_map(
                    function (string $className) use ($componentName, &$arguments) {
                        // if a valid $className was given, we continue
                        if (stristr($className, "\\" . ucfirst($componentName) . "\\") !== false) {
                            // if the $arguments array is not empty, we simply instantiate $componentName
                            if (count($arguments) == 0) {
                                $obj = new $componentName;
                            }
                            // else we call $className constructor using given $arguments and Reflection class
                            else {
                                $r = new \ReflectionClass($className);
                                $obj = $r->newInstanceArgs($arguments);
                            }
                            return $obj;
                        }
                        // else we stop with a null that will be filtered later
                        else {
                            return null;
                        }
                    },
                    // get all declared class names @see http://php.net/manual/pl/function.get-declared-classes.php
                    \get_declared_classes()
                )
            )
            )
        );
    }

    /**
     * Returns a new Atlas instance
     * @return Atlas
     */
    public function getAtlas() : Atlas
    {
        $arr = include join(DIRECTORY_SEPARATOR, [__DIR__, "..", "..", "..", ".atlas-config.php"]);
        return Atlas::new(
            $arr['pdo'][0],
            $arr['pdo'][1],
            $arr['pdo'][2]
        );
    }

    /**
     * @param LoggerInterface $logger
     * @return void
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
