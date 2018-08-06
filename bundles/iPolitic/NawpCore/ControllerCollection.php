<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/5/2018
 * Time: 7:46 PM
 */

namespace App\iPolitic\NawpCore;
use Bike\Router;
/**
 * Class ControllerCollection
 * Provide storage and match for a controller list
 * @package App\iPolitic\NawpCore
 */
class ControllerCollection extends Collection {
    
     /**
     * ControllerCollection constructor.
     * @param array $input
     * @param int $flags
     * @param string $iterator_class
     */
    public function __construct($input = array(), int $flags = 0, string $iterator_class = "ArrayIterator")
    {
        parent::__construct($input, $flags, $iterator_class);
    }
    /**
     *  Will run all controllers and reassign $response while the
     * Controller collection ->  handle() didn't returned TRUE
     * @param $response
     * @param $requestType
     * @param $requestArgs
     * @return bool
     * @throws \Exception
     */
    public function handle(&$response, $requestType, $requestArgs): void {
        $queue = $this->getOrderdByPriority();
        var_dump($queue);
        foreach($queue as $func => $funcArr) {
            if($funcArr["router"][1] === "*") {
                $routerResponse = ["dot let me empty so I can match"];
            } else {
                $dynamicRouter = new Router();
                $dynamicRouter->add($funcArr["controller"]."::".$funcArr["method"], [
                    "method" => $funcArr["router"][0],
                    "route" => $funcArr["router"][1]
                ]);
                $routerResponse = $dynamicRouter->match("POST", "http://home.com/");
            }
            if(!empty($routerResponse)) {
                if ($this->getByName($funcArr["controller"])->call($response, $funcArr["method"], $routerResponse)) {
                    break;
                }
            }
        }
    }


    /**
     * Will the current controller array orded by their priority
     * @return array
     * @throws \Exception
     */
    public function getOrderdByPriority() {
        $queue = [];
        /**
         * Copy all controllers methods to queue and add controller name as methods params
         * @var $v Controller
         */
        foreach ($this->getArrayCopy() as $v) {
            if (isset($v->methods) && is_array($v->methods)) {
                $arr = $v->methods;
                foreach ($v->methods as $k => $u) {
                    $arr[$k]["controller"] = $v->name;
                }
                $queue += $arr;
            } else {
                throw new \Exception("Empty controller");
            }
        }
        // order by priority value
        usort($queue,  function ($a, $b)
        {
            return ($a["priority"] === $b["priority"]) ? 0 : ($a["priority"] > $b["priority"]) ? -1 : 1;
        });
        return $queue;
    }

    /**
     * Will return a new controller using its namespaced name
     * @param string $controllerName
     * @return Controller
     */
    public function getByName(string $controllerName): Controller {
        return array_filter($this->getArrayCopy(), function($controller)use($controllerName){
            return $controller->name === $controllerName;
        })[0];
    }
}