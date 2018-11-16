<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/5/2018
 * Time: 7:47 PM
 */
namespace App\Ipolitic\Nawpcore;

use App\Ipolitic\Nawpcore\Collections\ControllerCollection;
use App\Ipolitic\Nawpcore\Collections\MiddlewareCollection;
use App\Ipolitic\Nawpcore\Collections\ViewCollection;
use App\Ipolitic\Nawpcore\Components\Collection;
use App\Ipolitic\Nawpcore\Components\Factory;
use App\Ipolitic\Nawpcore\Components\Logger;
use App\Ipolitic\Nawpcore\Components\Packet;
use App\Ipolitic\Nawpcore\Components\Requests;
use App\Ipolitic\Nawpcore\Components\SQL;
use App\Ipolitic\Nawpcore\Factories\CacheFactory;
use App\Server\PsrFactories;
use App\Server\PsrMiddlewares;
use Fabfuel\Prophiler\DataCollector\Request;
use Fabfuel\Prophiler\Profiler;
use Jasny\HttpMessage\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Simple\FilesystemCache;
use App\Ipolitic\Nawpcore\Components\Utils;
use App\Ipolitic\Nawpcore\Components\ViewLogger;
use Atlas\Orm\Atlas;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Dotenv\Dotenv;

class Kernel implements LoggerAwareInterface
{
    public const MAX_INC_DEEP = 10;
    public const CACHE_FOLDER_NAME = "cache";
    public const ROOT_PATH = [__DIR__, "..", "..", ".."];
    public const FRAMEWORK_FOLDERS = [
        "bundles",
        "src",
    ];
    public const ENV_PATH = ["configs", ".env"];
    /**
     * @var Kernel
     */
    public static $kernel;
    /**
     * @var string
     */
    public static $currentRequestType = "";
    /**
     * @var array
     */
    public static $currentPacket = [];
    /**
     * @var bool
     */
    public static $PHPUNIT_MODE = false;
    /**
     * @var LoggerInterface
     */
    public static $profilerLogger;
    /**
     * @var Profiler
     */
    public static $profiler;
    /**
     * @var Requests
     */
    public static $requests;
    /**
     * @var PsrFactories
     */
    public $factories = [];
    /**
     * @var Logger
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
     * @var MiddlewareCollection
     */
    public $middlewareCollection;
    /**
     * @var SQL
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
     * Kernel constructor
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws Exceptions\InvalidImplementation
     */
    public function __construct($bool = true)
    {
        // means path/to/project/root/ using defined constants
        $prefix = join(DIRECTORY_SEPARATOR, self::ROOT_PATH) . DIRECTORY_SEPARATOR;
        // include all project files
        foreach (self::FRAMEWORK_FOLDERS as $v) {
            if ($bool || $v == "bundles") {
                Kernel::loadDir($prefix . $v);
            }
        }
        $dotEnv = new Dotenv();
        // load .env file
        $dotEnv->load($prefix . join(DIRECTORY_SEPARATOR, self::ENV_PATH));
        // populate kernel collections and member variables
        $this->init();
        self::$kernel = $this;
    }

    /**
     * Wil recursivly require_once all files in the given directory
     * @param string $directory
     * @param int $deep
     */
    public static function loadDir(string $directory, int $deep = 0): void
    {
        if ($deep > self::MAX_INC_DEEP) {
            return;
        }
        if (is_dir($directory)) {
            $scan = scandir($directory);
            unset($scan[0], $scan[1]); //unset . and ..
            if (!file_exists($directory. DIRECTORY_SEPARATOR . ".noInclude")) {
                foreach ($scan as $file) {
                    if (is_dir($directory.DIRECTORY_SEPARATOR.$file)) {
                        self::loadDir($directory.DIRECTORY_SEPARATOR.$file, $deep + 1);
                    } else {
                        if (strpos($file, '.php') !== false) {
                            require_once($directory.DIRECTORY_SEPARATOR.$file);
                        }
                    }
                }
            }
        }
    }

    /**
     *  Will handle a request
     * @param string $requestType
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param Packet|null $packet
     * @param array $array
     * @param ViewLogger|null $viewLogger
     * @throws \iPolitic\Solex\RouterException
     * @throws \Exception
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function handle(ServerRequestInterface &$request, ResponseInterface &$response, string $requestType, $packet = null, $array = [], &$viewLogger = null): void
    {
        self::$requests->requests[] = $request;
        $this->controllerCollection->handle($this, $request, $response, $requestType, $packet, $array, $viewLogger);
    }

    /**
     * @throws Exceptions\InvalidImplementation
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function init(): void
    {
        date_default_timezone_set(getenv("TIMEZONE"));
        $this->factories       = new PsrFactories($this);
        $this->setLogger($this->factories->getLoggerFactory()->createLogger());
        // set memory to 4go
        ini_set('memory_limit', '2048M');
        $this->cachePath                = join(DIRECTORY_SEPARATOR, [__DIR__, "..", "..", "..", self::CACHE_FOLDER_NAME]);
        $this->controllerCollection     = new ControllerCollection();
        $this->middlewareCollection     = new MiddlewareCollection();
        $this->viewCollection           = new ViewCollection();
        $this->controllerCollection     ->setLogger($this->logger);
        $this->viewCollection           ->setLogger($this->logger);
        $this->middlewareCollection     ->setLogger($this->logger);
        $this->atlas                    = $this->getAtlas();
        self::$requests                 = new Requests();
        $this->packetAdapterCache       = (new CacheFactory(
            'Symfony\Component\Cache\Simple\FilesystemCache',
        ['' , 0, join(DIRECTORY_SEPARATOR, [$this->cachePath, "packetAdapter"])]
        ))->createCache();
        $this->sessionCache             = (new CacheFactory(
            'Symfony\Component\Cache\Simple\FilesystemCache',
        ['', 0, join(DIRECTORY_SEPARATOR, [$this->cachePath, "session"])]
        ))->createCache();

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
        $params = [&$this];
        foreach ((new PsrMiddlewares())->process($this) as $v) {
            $this->middlewareCollection->append($v);
        }
        $this->fillCollectionWithComponents($this->middlewareCollection, $params, 'middlewares');
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
        // foreach components
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
                                    $obj = new $className;
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
        return new SQL(
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
