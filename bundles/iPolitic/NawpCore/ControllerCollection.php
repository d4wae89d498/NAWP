<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/5/2018
 * Time: 7:46 PM
 */

namespace App\iPolitic\NawpCore;

/**
 * Class ControllerCollection
 * Provide storage and match for a controller list
 * @package App\iPolitic\NawpCore
 */
class ControllerCollection extends Collection {
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
           var_dump($this->getByName($funcArr["controller"])->call($response, $funcArr["method"], $requestArgs));
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