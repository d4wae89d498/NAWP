<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/5/2018
 * Time: 7:47 PM
 */

namespace App\iPolitic\NawpCore;

use App\iPolitic\NawpCore\Collections\ControllerCollection;
use Atlas\Orm\Atlas;
use Atlas\Orm\Mapper\Mapper;
use Atlas\Orm\AtlasContainer;
use App\DataSources\{
    Categorie\CategorieMapper,
    Log\LogMapper,
    ContentsCategories\ContentsCategoriesMapper,
    Translation\TranslationMapper,
    User\UserMapper,
    Content\ContentMapper
};

class Kernel {
    public $entityManager;


    public function __construct()
    {
        $this->init();
    }

    /**
     * @var ControllerCollection
     */
    public $controllerCollection;
    public $atlas;

    /**
     * Wil recursivly require_once all filesinthe given directory
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
     * @throws \Exception
     */
    public function handle(&$response, string $requestType, $requestArgs): void {
        $this->controllerCollection->handle($response, $requestType, $requestArgs);
    }

    /**
     * Will boot the
     */
    public function init(): void
    {
        $this->controllerCollection = new ControllerCollection();

        $arr = include join(DIRECTORY_SEPARATOR,[__DIR__,"..","..","..","atlas-config.php"]);

        $atlasContainer = new AtlasContainer($arr[0], $arr[1], $arr[2]);

        $atlasContainer->setMappers([
            UserMapper::CLASS,
            TranslationMapper::CLASS,
            CategorieMapper::class,
            ContentMapper::class,
            LogMapper::class,
            ContentsCategoriesMapper::class,
        ]);

        $this->atlas = $atlasContainer->getAtlas();
        /*
        $config = Setup::createAnnotationMetadataConfiguration
        (
            [join(DIRECTORY_SEPARATOR, [__DIR__, "..", "..", "..", "src"])],
            true
        );
        $conn = [
            'driver' => 'pdo_sqlsrv',
           'user' => 'sa',
           'password' => '_e3oCWaW#',
           'port'=> 1433,
        ];
        $this->entityManager = EntityManager::create($conn, $config);
        var_dump($this->entityManager);*/
    }

    /**
     * Will instantiate all controllers declared in a "controllers" folder following PSR standars
     */
    public function instantiateControllers(): void {
        // foreach controllers
        array_map
        (
            function($controller) {
                /**
                 * @var Controller $controller the controller instance that will be added to the controller collection
                 */
                $this->controllerCollection->append($controller);
            },
            (
            // remove null values
            array_filter
            (
                // convert declared class name to controller instance if match, or null value
                array_map
                (
                    function ($class) {
                        /**
                         * @var string $class
                         */
                        //
                        return (stristr($class, "\\Controllers\\") !== false) ? new $class($this->atlas) : null;
                    },
                    // get all declared class names @see http://php.net/manual/pl/function.get-declared-classes.php
                    \get_declared_classes()
                )
            ))
        );
    }

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
}