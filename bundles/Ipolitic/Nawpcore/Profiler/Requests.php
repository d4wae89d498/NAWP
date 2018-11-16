<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 11/2/2018
 * Time: 2:30 PM
 */

namespace App\Ipolitic\Nawpcore\Components;

use Fabfuel\Prophiler\DataCollectorInterface;
use Jasny\HttpMessage\ServerRequest;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ProfilerRequest
 * @package App\Ipolitic\Nawpcore\Components
 */
class Requests implements DataCollectorInterface
{
    /**
     * @var RequestInterface[]
     */
    public $requests = [];

    /**
     * @return string
     */
    public function getTitle() : string
    {
        return "Requests";
    }

    /**
     * @return string
     */
    public function getIcon() : string
    {
        return "<i class=\"fa fa-arrow-circle-o-down\"></i>";
    }

    /**
     * @return array
     */
    public function getData() : array
    {
        $startIndex = ($cnt = count($this->requests) - getenv("REQUEST_STACK_SIZE")) > 0 ? $cnt : 0;
        $length = count($this->requests) - $startIndex;
        $this->requests = array_slice($this->requests, $startIndex, $length);

        $outputArr = [];

        /**
         * @var $request ServerRequest
         */
        foreach ($this->requests as $requestKey => $request) {
            $servkeys = [   "PHP_SELF",
                "argv",
                "argc",
                "GATEWAY_INTERFACE",
                "SERVER_ADDR",
                "SERVER_NAME",
                "SERVER_SOFTWARE",
                "SERVER_PROTOCOL",
                "REQUEST_METHOD",
                "REQUEST_TIME",
                "REQUEST_TIME_FLOAT",
                "QUERY_STRING",
                "DOCUMENT_ROOT",
                "HTTP_ACCEPT",
                "HTTP_ACCEPT_CHARSET",
                "HTTP_ACCEPT_ENCODING",
                "HTTP_ACCEPT_LANGUAGE",
                "HTTP_CONNECTION",
                "HTTP_HOST",
                "HTTP_REFERER",
                "HTTP_USER_AGENT",
                "HTTPS",
                "REMOTE_ADDR",
                "REMOTE_HOST",
                "REMOTE_PORT",
                "REMOTE_USER",
                "REDIRECT_REMOTE_USER",
                "SCRIPT_FILENAME",
                "SERVER_ADMIN",
                "SERVER_PORT",
                "SERVER_SIGNATURE",
                "PATH_TRANSLATED",
                "SCRIPT_NAME",
                "REQUEST_URI",
                "PHP_AUTH_DIGEST",
                "PHP_AUTH_USER",
                "PHP_AUTH_PW",
                "AUTH_TYPE",
                "PATH_INFO",
                "ORIG_PATH_INFO"];

            $headers = [];
            foreach ($request->getHeaders() as $name => $values) {
                $headers[$name] = implode(", ", $values);
            }
            $server = $request->getServerParams();
            foreach ($_ENV as $k => $v) {
                if (isset($_ENV[$k])) {
                    unset($server[$k]);
                }
            }
            $scopy = $server;
            foreach ($server as $k => $v) {
                if (!in_array($k, $servkeys)) {
                    unset($scopy[$k]);
                }
            }
            $scopy["REQUEST_TIME"] = microtime();
            $scopy["REQUEST_TIME_FLOAT"] = microtime(true);
            $outputArr[] = "<div class=\"jsonEncoded\" id=\"request_".$requestKey."\">" . json_encode([
                    "SERVER"        => $scopy,
                    "QUERY"         => $request->getQueryParams(),
                    "COOKIES"       => $request->getCookieParams(),
                    "HEADERS"       => $headers,
                    "ATTRIBUTES"    => $request->getAttributes(),
                    "POST"          => ($res = $request->getParsedBody()) != null ? $res : []
            ]) . "</div>";
        }
        return $outputArr;
    }
}
