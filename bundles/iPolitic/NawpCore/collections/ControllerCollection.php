<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/5/2018
 * Time: 7:46 PM
 */

namespace App\iPolitic\NawpCore\Collections;

use iPolitic\Solex\Router;
use App\iPolitic\NawpCore\Components\{Collection};
use App\iPolitic\NawpCore\Interfaces\ControllerInterface;
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
    public function __construct(array $input = [], int $flags = 0, string $iterator_class = "ArrayIterator")
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
        // for each controller methods ordered by prioriy
        foreach($this->getOrderdByPriority() as $controllerMehod) {
            //var_dump($controllerMehod);
            // we force a match if wildcard used
            if($controllerMehod["router"][1] === "*") {
                $routerResponse = ["dot let me empty so I can match"];
            } else {
                // create a new router for that method
                $dynamicRouter = new Router();
                // add the route then match
                $dynamicRouter->add($controllerMehod["controller"]."::".$controllerMehod["method"], [
                    "method" => $controllerMehod["router"][0],
                    "route" => $controllerMehod["router"][1]
                ]);
                $routerResponse = $dynamicRouter->match($requestType, $requestArgs);
            }
            // execute controller method if router matched or wildecas used
            if(!empty($routerResponse)) {
                if ($this->getByName($controllerMehod["controller"])->call($response, $controllerMehod["method"], $routerResponse)) {
                    break;
                }
            }
           // var_dump($routerResponse);
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
         * @var $v ControllerInterface
         */
        foreach ($this->getArrayCopy() as $v) {
            if ($v instanceof ControllerInterface && is_array($methods = $v->getMethods())) {
                $arr = $methods;
                foreach ($methods as $k => $u) {
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
    public function getByName(string $controllerName): ControllerInterface {
        return array_filter
        (
            $this->getArrayCopy(),
            function($controller)use($controllerName){
                return $controller->name === $controllerName;
            }
        )
        [0]
        ;
    }
}