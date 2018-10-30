<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/5/2018
 * Time: 7:46 PM
 */
namespace App\Ipolitic\Nawpcore\Collections;

use Jasny\HttpMessage\Stream;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use iPolitic\Solex\Router;
use App\Ipolitic\Nawpcore\Kernel;
use App\Ipolitic\Nawpcore\Components\Cookie;
use App\Ipolitic\Nawpcore\Components\Collection;
use App\Ipolitic\Nawpcore\Components\Controller;
use App\Ipolitic\Nawpcore\Components\PacketAdapter;
use App\Ipolitic\Nawpcore\Components\Utils;
use App\Ipolitic\Nawpcore\Components\ViewLogger;
use App\Ipolitic\Nawpcore\Interfaces\ControllerInterface;

/**
 * Class ControllerCollection
 * Provide storage and match for a controller list
 * @package App\Ipolitic\Nawpcore
 */
class ControllerCollection extends Collection implements LoggerAwareInterface
{
    /**
     * @var LoggerInterface
     */
    public $logger;
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
     * @param Kernel $kernel
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param string $requestType
     * @param mixed $packet
     * @param array $array
     * @param ViewLogger|null $viewLogger
     * @throws \iPolitic\Solex\RouterException
     * @throws \Exception
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function handle(Kernel &$kernel, ServerRequestInterface &$request, ResponseInterface &$response, $requestType, $packet = null, $array = [], $viewLogger = null): void
    {
        $viewLogger = $viewLogger !== null ? $viewLogger : new ViewLogger($kernel, $request, $array, $packet, $requestType);
        if (isset($_ENV["CLEAR_COOKIES"]) && (((int) $_ENV["CLEAR_COOKIES"]) === 1)) {
            $viewLogger->cookiePoolInstance->destroy();
        }
        // redirecting to the same page with needed UID param if none where passed to $_SERVER REQUEST URI
        if (!$viewLogger->cookiePoolInstance->areCookieEnabled()) {
            if (isset($request->getServerParams()["HTTP_REFERER"])) {
                $parsedHttpReferer = Utils::parseUrlParams($request->getServerParams()["HTTP_REFERER"]);
                $parsedHttpUri = $params = Utils::parseUrlParams($request->getServerParams()["HTTP_REFERER"]);
                if (isset($parsedHttpReferer["UID"]) && !isset($parsedHttpUri["UID"])) {
                    $params["UID"] = $parsedHttpReferer["UID"];
                    if (!stristr($request->getServerParams()["REQUEST_URI"], "logout")) {
                        $viewLogger->redirectTo
                        (
                            $response,
                            Utils::buildUrlParams($request->getServerParams()["REQUEST_URI"], $params),
                            $array
                        );
                        if (isset($_ENV["CLEAR_COOKIES"]) && (((int) $_ENV["CLEAR_COOKIES"]) === 1)) {
                            $viewLogger->cookiePoolInstance->destroy();
                        }
                        return;
                    }
                }
            }
        }
        // removing for disallowed cookie
        foreach ($viewLogger->cookiePoolInstance->getAll() as $k => $v) {
            if (!$viewLogger->cookiePoolInstance->isAllowedCookie($k)) {
                $viewLogger->cookiePoolInstance->remove($k);
            } else {
                $viewLogger->cookiePoolInstance->set(new Cookie($k, $v), true);
            }
        }

        $controllerMethodsCalled = [];
        // for each controller methods ordered by priority
        foreach ($this->getOrderedByPriority($request) as $controllerMethod) {
            // we force a match if wildcard used
            if ($controllerMethod["router"][1] === "*") {
                $routerResponse = [""];
            } else {
                // create a new router for that method
                $dynamicRouter = new Router();
                // add the route then match
                $dynamicRouter->add($controllerMethod["controller"]."::".$controllerMethod["method"], [
                    "method" => $controllerMethod["router"][0],
                    "route" => $controllerMethod["router"][1]
                ]);
                $routerResponse = $dynamicRouter->match(
                    $requestType,
                    $request->getServerParams()["REQUEST_URI"]
                );
            }
            // execute controller method if router matched or wildecas used
            if (!empty($routerResponse)) {
                /**
                 * @var $controller Controller
                 */
                $controllerMethod["controller"] = "\\" . $controllerMethod["controller"];
                $controller = new $controllerMethod["controller"]($kernel->atlas, $kernel->logger);
                array_push(
                    $controllerMethodsCalled,
                    ($arr = explode("\\", $controller->name))[count($arr) - 1]
                    . "::". $controllerMethod["method"]
                );
                if ($controller->call($viewLogger, $response, $controllerMethod["method"], $routerResponse)) {
                    // nothing special to do right now
                    break;
                }
            }
        }
        if ($packet !== null) {
            $serverGenerated = $viewLogger->renderedTemplates;
            $newBody = new Stream();
            $newBody->write(json_encode($serverGenerated));
            $response = $response->withBody($newBody);
            var_dump((string) $response->getBody());
        }
        $toLog = "";
        if (isset($_ENV["LOG_REQUEST"]) && (((int) $_ENV["LOG_REQUEST"]) === 1)) {
            $toLog .= "[".$requestType."] - '".$request->getServerParams()["REQUEST_URI"]."' =-=|> '".join(" -> ", $controllerMethodsCalled)."'" . PHP_EOL;
        }
        if (isset($_ENV["LOG_POST"]) && (((int) $_ENV["LOG_POST"]) === 1)) {
            $toLog .= " * post -> " . json_encode($_POST) . PHP_EOL;
        }
        if (isset($_ENV["LOG_GET"]) && (((int) $_ENV["LOG_GET"]) === 1)) {
            $toLog .= " * get -> " . json_encode($_POST) . PHP_EOL;
        }
        if (isset($_ENV["LOG_COOKIE"]) && (((int) $_ENV["LOG_COOKIE"]) === 1)) {
            $toLog .= " * cookies -> : " . json_encode($viewLogger->cookiePoolInstance->getAll()) . PHP_EOL;
        }
        if (isset($_ENV["LOG_SESSION"]) && (((int) $_ENV["LOG_SESSION"]) === 1)) {
            $toLog .= " * session -> : " . json_encode($viewLogger->sessionInstance->getAll()) . PHP_EOL;
        }
        if (!empty($toLog)) {
            $this->logger->info($toLog);
        }
        if (isset($_ENV["CLEAR_COOKIES"]) && (((int) $_ENV["CLEAR_COOKIES"]) === 1)) {
            $viewLogger->cookiePoolInstance->destroy();
        }
        return;
    }

    /**
     * Will the current controller array orded by their priority
     * @param ServerRequestInterface $request
     * @return array
     */
    public function getOrderedByPriority(ServerRequestInterface &$request): array
    {
        $queue = [];
        /**
         * Copy all controllers methods to queue and add controller name as methods params
         * @var $v ControllerInterface
         */
        foreach ($this->getArrayCopy() as $v) {
            if ($v instanceof Controller && is_array($methods = $v->getMethods())) {
                foreach ($methods as $k => $u) {
                    //  echo "method : " . $u["method"] . PHP_EOL;
                    $rqType = $methods[$k]["router"][0];
                    if (
                        (($request->getServerParams()["REQUEST_URI"] === "*") || ($rqType === "*")) ||
                        ($request->getServerParams()["REQUEST_URI"] === $rqType)
                    ) {
                        $methods[$k]["controller"] = $v->name;
                        array_push($queue, $methods[$k]);
                    }
                }
            }
        }
        // order by priority value
        usort($queue, function ($a, $b) {
            return ($a["priority"] === $b["priority"]) ? 0 : ($a["priority"] > $b["priority"]) ? -1 : 1;
        });
        return $queue;
    }

    /**
     * Will set the logger interface following PSR recommendations
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
