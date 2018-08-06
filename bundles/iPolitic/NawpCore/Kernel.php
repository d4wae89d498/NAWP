<?php

namespace App\iPolitic\NawpCore;

/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/5/2018
 * Time: 7:47 PM
 */

class Kernel {

    /**
     * @var ControllerCollection
     */
    public static $controllerCollection;

    /**
     * Kernel constructor.
     */
    public function __construct()
    {
        $this->boot();
    }

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
                    if(strpos($file, '.php') !== false) {
                        include_once($directory."/".$file);
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
        self::$controllerCollection->handle($response, $requestType, $requestArgs);
    }

    /**
     * Will boot the
     */
    public function boot(): void
    {
        self::$controllerCollection = new ControllerCollection();
    }

    /**
     * Wakeup magic funcion, used to reinit thhe controllerCollection 
     */
    public function __wakeup()
    {
        // TODO: Implement __wakeup() method.
        $this->boot();
    }
}