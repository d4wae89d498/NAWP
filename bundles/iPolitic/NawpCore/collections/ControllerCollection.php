<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/5/2018
 * Time: 7:46 PM
 */

namespace App\iPolitic\NawpCore\Collections;

use iPolitic\Solex\Router;
use App\iPolitic\NawpCore\Components\{
    Collection, Controller, ViewLogger
};
use App\iPolitic\NawpCore\Interfaces\ControllerInterface;
use phpseclib\Math\BigInteger\Engines\PHP;

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
     * Will run all controllers and reassign $response while the
     * Controller collection ->  handle() didn't returned TRU
     * @param $response
     * @param $requestType
     * @param $requestArgs
     * @param $packet
     * @throws \iPolitic\Solex\RouterException
     */
    public function handle(&$response, $requestType, $requestArgs, $packet = null, $array): void {
        $_GET = $GLOBALS["_GET"] = parse_url($_SERVER["REQUEST_URI"]);
        $response = "";
        $viewLogger = new ViewLogger($array, $requestType);
        // for each controller methods ordered by priority
        foreach($this->getOrderdByPriority() as $controllerMethod) {
            //var_dump($controllerMehod);
            // we force a match if wildcard used
            if($controllerMethod["router"][1] === "*") {
                $routerResponse = [""];
            } else {
                // create a new router for that method
                $dynamicRouter = new Router();
                // add the route then match
                $dynamicRouter->add($controllerMethod["controller"]."::".$controllerMethod["method"], [
                    "method" => $controllerMethod["router"][0],
                    "route" => $controllerMethod["router"][1]
                ]);
                $routerResponse = $dynamicRouter->match($requestType,
                    is_array($requestArgs) && isset($requestArgs['url']) ?
                        $requestArgs['url'] :
                            (is_string($requestArgs) ? $requestArgs : ""));

            }
            // execute controller method if router matched or wildecas used
            if(!empty($routerResponse)) {
                /**
                 * @var $controller Controller
                 */
                $controller = $this->getByName($controllerMethod["controller"]);
                if ($controller->call($viewLogger, $response, $controllerMethod["method"], $routerResponse, $requestType)) {
                    // nothing special to do right now
                    break;
                }
            }
        }

        if ($packet !== null) {
           // var_dump($packet);
            $toSend = [];
            $serverGenerated = $viewLogger->renderedTemplates;
            $response = json_encode($serverGenerated);
            var_dump($response);
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
                foreach ($methods as $k => $u) {
                  //  echo "method : " . $u["method"] . PHP_EOL;
                    $methods[$k]["controller"] = $v->name;
                    array_push($queue, $methods[$k]);
                }
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
     * @return ControllerInterface
     */
    public function getByName(string $controllerName): ControllerInterface {
        $match = null;
        $controllers =  $this->getArrayCopy();
        $a =  array_filter
        (
            $controllers,
            function($controller)use($controllerName, &$match) {
               $success = ($controller->name === $controllerName);
                if($success) {
                    $match = $controller;
                }
                return $success;
            }
        )
        ;
        if($match === null) {
            echo " no controller foudn for : " . $controllerName . PHP_EOL;
        }
        return $match ;
    }
}