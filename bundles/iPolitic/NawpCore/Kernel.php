<?php
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
use App\iPolitic\NawpCore\components\PacketAdapter;
use App\iPolitic\NawpCore\Components\Session;
use Atlas\Orm\Atlas;
use Atlas\Orm\Mapper\Mapper;
use Atlas\Orm\AtlasContainer;
use phpseclib\Crypt\RSA;
use Symfony\Component\Dotenv\Dotenv;
use App\DataSources\{
    Categorie\CategorieMapper,
    Log\LogMapper,
    ContentsCategories\ContentsCategoriesMapper,
    Translation\TranslationMapper,
    User\UserMapper,
    Content\ContentMapper
};

class Kernel {
    public const CACHE_FOLDER_NAME = "cache";
    public const RSA_FILE_NAME = "rsa.txt";
    public const DEFAULT_RSA_KEYS = [];
    /**
     * @var array
     */
    public $rsaKeys = self::DEFAULT_RSA_KEYS;
    /**
     * @var string
     */
    public $cachePath = "";
    /**
     * @var ControllerCollection
     */
    public $controllerCollection;
    /**
     * @var ViewCollection
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
    public static function setKernel(&$kernel): void {
        self::$kernel = $kernel;
    }

    /**
     * @return Kernel
     */
    public static function getKernel(): Kernel {
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
     * Wil recursivly require_once all files in the given directory
     * @param string $directory
     */
    public static function loadDir(string $directory): void {
        if(is_dir($directory)) {
            $scan = scandir($directory);
            unset($scan[0], $scan[1]); //unset . and ..
            foreach($scan as $file) {
                if(is_dir($directory."/".$file)) {
                    self::loadDir($directory."/".$file);
                } else {
                    if (!file_exists($directory."/.noInclude")) {
                        if(strpos($file, '.php') !== false) {
                            require_once($directory."/".$file);
                        }
                    }
                }
            }
        }
    }

    /**
     * Will handle a request
     * @param $response
     * @param string $requestType
     * @param $requestArgs
     * @param bool $useRouterResult
     * @throws \iPolitic\Solex\RouterException
     */
    public function handle(&$response, string $requestType, $requestArgs,  bool $useRouterResult = true): void {
        $this->controllerCollection->handle($response, $requestType, $requestArgs, $useRouterResult);
    }

    /**
     * Will boot the
     */
    public function init(): void
    {
        $this->cachePath = join(DIRECTORY_SEPARATOR, [__DIR__, "..", "..", "..", self::CACHE_FOLDER_NAME]);
        $this->controllerCollection = new ControllerCollection();
        $this->viewCollection = new ViewCollection();
        $this->atlas = $this->getAtlas();
        //$this->loadRSA();
        self::setKernel($this);
        PacketAdapter::init();
        Session::init();
    }

    /**
     * Will instantiate all components declared in a "$components" folder following PSR standars
     * @param string $componentName
     */
    public function fillCollectionWithComponents(Collection &$collection, array &$arguments = [], string $componentName): void {
        // foreach controllers
        array_map
        (
            function($component) use (&$collection) {
                /**
                 * @var Controller $controller the controller instance that will be added to the controller collection
                 */
                $collection->append($component);
            },
            (
            // remove null values
            array_filter
            (
                // convert declared class name to controller instance if match, or null value
                array_map
                (

                    function (string $className) use ($componentName, &$arguments) {
                        // if a valid $className was given, we continue
                        if( stristr($className, "\\" . ucfirst($componentName) . "\\") !== false ) {
                            // if the $arguments array is no empty, we simply instantiate $componentName
                            if(count($arguments) == 0)
                                $obj = new $componentName;
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
            ))
        );
    }

    /**
     * Returns a new Atlas instance
     * @return Atlas
     */
    public function getAtlas() : Atlas {
        $arr = include join(DIRECTORY_SEPARATOR, [__DIR__, "..", "..", "..", "atlas-config.php"]);
        $atlasContainer = new AtlasContainer($arr[0], $arr[1], $arr[2]);
        $atlasContainer->setMappers([
            UserMapper::CLASS,
            TranslationMapper::CLASS,
            CategorieMapper::class,
            ContentMapper::class,
            LogMapper::class,
            ContentsCategoriesMapper::class,
        ]);
        return $atlasContainer->getAtlas();
    }

    public function loadRSA(): void {
        $rsaFilePath = join(DIRECTORY_SEPARATOR, [$this->cachePath, self::RSA_FILE_NAME]);
        $keys = [];
        if (!file_exists($rsaFilePath)) {
            $handle = fopen($rsaFilePath, "w+");
            $rsa = new RSA();
            $keys = $rsa->createKey(1024);
            fwrite($handle, serialize($keys));
        } else {
            $handle = fopen($rsaFilePath, "r+");
            $keys = unserialize(fread($handle, filesize($rsaFilePath)));
        }
        $this->rsaKeys = $keys;
    }

}