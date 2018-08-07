<?php

namespace App\iPolitic\NawpCore;

/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/5/2018
 * Time: 7:47 PM
 */

class Kernel {

    public function __construct()
    {
        $this->init();
    }

    /**
     * @var ControllerCollection
     */
    public $controllerCollection;

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
                        return (stristr($class, "\\Controllers\\") !== false) ? new $class() : null;
                    },
                    // get all declared class names @see http://php.net/manual/pl/function.get-declared-classes.php
                    \get_declared_classes()
                )
            ))
        );
    }
}