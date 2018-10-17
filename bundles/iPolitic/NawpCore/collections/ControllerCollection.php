<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/5/2018
 * Time: 7:46 PM
 */
namespace App\iPolitic\NawpCore\Collections;

use App\iPolitic\NawpCore\components\Cookie;
use App\iPolitic\NawpCore\components\Packet;
use App\iPolitic\NawpCore\Kernel;
use iPolitic\Solex\Router;
use App\iPolitic\NawpCore\Components\{Collection, Controller, PacketAdapter, ViewLogger};
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
     * Will run all controllers and reassign $response while the
     * Controller collection ->  handle() didn't returned TRU
     * @param string $response
     * @param string $requestType
     * @param string $requestArgs
     * @param mixed $packet
     * @param array $array
     * @param $viewLogger|null ViewLogger
     * @throws \iPolitic\Solex\RouterException
     * @throws \Exception
     */
    public function handle(&$response, $requestType, $requestArgs, $packet = null, $array = [], $viewLogger = null): void {
        $_GET = $GLOBALS["_GET"] = parse_url($_SERVER["REQUEST_URI"]);
        $response = "";
        $viewLogger = $viewLogger !== null ? $viewLogger : new ViewLogger($array, $packet, $requestType);
        if (!Cookie::areCookieEnabled($viewLogger)) {
            if(isset($_SERVER["HTTP_REFERER"]) && isset(($prevID = parse_url($_SERVER["HTTP_REFERER"]))["UID"]) && !isset( ($url = parse_url($_SERVER["REQUEST_URI"]))["UID"])) {
                $url["UID"] = $prevID;
                if ((($urlWithoutArgs = $_SERVER["REQUEST_URI"])[0]) !== "logout") {
                    PacketAdapter::redirectTo(
                        $response,
                        $viewLogger,
                        explode("?",$_SERVER["REQUEST_URI"])[0] . "?" . http_build_query($url),
                        $array
                    );
                    return;
                }
            }
        }
        if ($requestType !== "SOCKET") {
            echo "FILTERING ...." . PHP_EOL;
            // removing for disallowed cookie
            foreach (Cookie::getHttpCookies() as $k => $v) {
                if (!Cookie::isAllowedCookie($k)) {
                    echo "COOKIE REMOVED : " . $k . PHP_EOL;
                    Cookie::remove($viewLogger, $k);
                } else {
                    Cookie::set($viewLogger, new Cookie($k, $v), true);
                }
            }
        }
        // for each controller methods ordered by priority
        foreach($this->getOrderedByPriority() as $controllerMethod) {
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
                if ($controller->call($viewLogger, $response, $controllerMethod["method"], $routerResponse)) {
                    // nothing special to do right now
                    break;
                }
            }
        }
        if ($packet !== null) {
            $serverGenerated = $viewLogger->renderedTemplates;
            $response = json_encode($serverGenerated);
        }
        return;
    }

    /**
     * Will the current controller array orded by their priority
     * @return array
     */
    public function getOrderedByPriority(): array {
        $queue = [];
        /**
         * Copy all controllers methods to queue and add controller name as methods params
         * @var $v ControllerInterface
         */
        foreach ($this->getArrayCopy() as $v) {
            if ($v instanceof Controller && is_array($methods = $v->getMethods())) {
                foreach ($methods as $k => $u) {
                  //  echo "method : " . $u["method"] . PHP_EOL;
                    $methods[$k]["controller"] = $v->name;
                    array_push($queue, $methods[$k]);
                }
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
     * Returns all controllers that match a controllerName
     * @param string $controllerName
     * @return array
     */
    public function getAllByName(string $controllerName): array {
        $matches = null;
        $controllers =  $this->getArrayCopy();
        $matches = array_filter
        (
            $controllers,
            function ($controller) use ($controllerName, &$match) {
                return ($controller->name === $controllerName);
            }
        );
        return $matches ;
    }

    /**
     * Will return a new controller using its namespace name
     * @param string $controllerName
     * @return Controller
     */
    public function getByName(string $controllerName): Controller {
        return
        (
            isset
            (
                (
                    $allByName = $this->getAllByName($controllerName)
                )
                [count($allByName) - 1]
            ) ?
                $allByName[count($allByName) - 1]
            :
                new Controller(Kernel::getKernel()->atlas)
        );
    }
}