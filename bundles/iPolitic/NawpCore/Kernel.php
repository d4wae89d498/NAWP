<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/5/2018
 * Time: 7:47 PM
 */
namespace App\iPolitic\NawpCore;

use App\Datasources\Content\Content;
use App\Datasources\ContentsCategory\ContentsCategory;
use App\iPolitic\NawpCore\Collections\ControllerCollection;
use App\iPolitic\NawpCore\Collections\ViewCollection;
use App\iPolitic\NawpCore\Components\Collection;
use App\iPolitic\NawpCore\components\Logger;
use App\iPolitic\NawpCore\components\Packet;
use App\iPolitic\NawpCore\Components\PacketAdapter;
use App\iPolitic\NawpCore\Components\Session;
use App\iPolitic\NawpCore\Components\ViewLogger;
use Atlas\Orm\Atlas;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Dotenv\Dotenv;
use App\DataSources\ContentsCategory\Contents;
use App\DataSources\Content\ContentMapper;

class Kernel implements LoggerAwareInterface
{
    public const CACHE_FOLDER_NAME = "cache";
    /**
     * @var array
     */
    public static $twigArray = [];

    /**
     * @var LoggerInterface $logger
     */
    public static $_logger;

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
     * @var Kernel
     */
    public static $kernel;

    /**
     * @param $kernel
     */
    public static function setKernel(&$kernel): void
    {
        self::$kernel = $kernel;
    }

    /**
     * @return Kernel
     */
    public static function getKernel(): Kernel
    {
        return self::$kernel;
    }

    /**
     * Kernel constructor.
     */
    public function __construct()
    {
        $dotEnv = new Dotenv();
        $dotEnv->load(join(DIRECTORY_SEPARATOR, [__DIR__, "..", "..", "..", "configs", ".env"]));
        $this->init();
    }

    /**
     * @return Logger
     */
    public static function cli(): LoggerInterface
    {
        return clone self::$_logger;
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
     * @param string $requestArgs
     * @param Packet|null $packet
     * @param array $array
     * @param ViewLogger|null $viewLogger
     * @throws \iPolitic\Solex\RouterException
     */
    public function handle(&$response, string $requestType, $requestArgs, $packet = null, $array = [], &$viewLogger = null): void
    {
        $this->controllerCollection->handle($response, $requestType, $requestArgs, $packet, $array, $viewLogger);
    }

    /**
     * Will boot the kernel
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
        //$this->loadRSA();
        self::setKernel($this);
        PacketAdapter::init();
        Session::init();
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
        self::$_logger = $this->logger = $logger;
    }
}
