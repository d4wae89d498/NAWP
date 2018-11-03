<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 11/2/2018
 * Time: 2:36 PM
 */

namespace App\Server\Middlewares;

use App\Ipolitic\Nawpcore\Components\Middleware;
use App\Ipolitic\Nawpcore\Components\Queries;
use App\Ipolitic\Nawpcore\Components\Requests;
use App\Ipolitic\Nawpcore\Kernel;
use Fabfuel\Prophiler\Adapter\Psr\Log\Logger;
use Fabfuel\Prophiler\Profiler;
use Fabfuel\Prophiler\Toolbar;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Workerman\Protocols\HttpCache;

/**
 * Class ProfilerMiddleware
 * @package App\Server\Middlewares
 */
class ProfilerMiddleware extends Middleware implements MiddlewareInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $profiler = new Profiler();
        $toolbar = new Toolbar($profiler);

        // SNIPET :: LOG REQUEST
        $toolbar->addDataCollector(new Requests($request));

        $response = $handler->handle($request);

        // SNIPET :: ADD A LOG TO THE PROFILER
        $benchmark = $toolbar->getProfiler()->start("TEST", ["severity" => "error"], 'Logger');
        $toolbar->getProfiler()->stop($benchmark);

        // SNIPET :: ADD LOG TO THE DATABASE
        $queries = new Queries();
        $queries->append("<pre><code class=\"sql hljs\">select * from \"users\"</code></pre>");
        $toolbar->addDataCollector($queries);

        if ((Kernel::$currentRequestType !== "SOCKET")) {
            // Allow any HTML content type
            $contentType = HttpCache::$header["Content-Type"];
            if (false === strpos($contentType,"text/")) {
                $this->kernel->logger->debug('Content-Type of response is not HTML. Skipping Prophiler toolbar generation.');
                return $response;
            }
            $body = $response->getBody();
            if (!$body->eof() && $body->isSeekable()) {
                $body->seek(0, SEEK_END);
            }
            $body->write($toolbar->render());
            $response->withBody($body);
        }
        return $response;
    }
}
