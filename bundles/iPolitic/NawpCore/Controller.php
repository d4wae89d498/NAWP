<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/5/2018
 * Time: 7:46 PM
 */

namespace App\iPolitic\NawpCore;

class Controller {
    /**
     * The controller name
     * @var string
     */
    public $name = "";

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->name = get_class($this);
    }

    /**
     * Will call a controller method
     * @param string $response
     * @param string $method
     * @param mixed $args
     * @return bool
     */
    public function call(string &$response, string $method, $args): bool {
        var_dump("IN CALLL" . $method);
        if (method_exists($this,$method)) {
            return $this->$method($response, $method, $args);
        } else {
            return false;
        }
    }
}