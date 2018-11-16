<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 11/2/2018
 * Time: 2:36 PM
 */

namespace App\Server\Middlewares;

use App\Ipolitic\Nawpcore\Collections\LoggerCollection;
use App\Ipolitic\Nawpcore\Components\Middleware;
use App\Ipolitic\Nawpcore\Components\Queries;
use App\Ipolitic\Nawpcore\Components\Requests;
use App\Ipolitic\Nawpcore\Kernel;
use Fabfuel\Prophiler\Adapter\Psr\Log\Logger;
use Fabfuel\Prophiler\Profiler;
use Fabfuel\Prophiler\Toolbar;
use Jasny\HttpMessage\Stream;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LogLevel;
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
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \Exception
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $profiler = new Profiler();
        $toolbar = new Toolbar($profiler);

        // SNIPET :: LOG REQUEST
        $toolbar->addDataCollector(Kernel::$requests);

        Kernel::$profilerLogger = new \Fabfuel\Prophiler\Adapter\Psr\Log\Logger($profiler);
        Kernel::$profiler = $toolbar->getProfiler();
        $benchmark =
        Kernel::$profiler->start(__METHOD__, ["severity" => "info"], ($arr = explode("\\", get_class()))[count($arr) - 1]);
        $response = $handler->handle($request);
        Kernel::$profilerLogger->debug("Test message.");
        // SNIPET :: ADD LOG TO THE DATABASE
        $queries = $this->kernel->atlas->queries;
        $toolbar->addDataCollector($queries);

        Kernel::$profiler->stop();

        // in http mode
        if ((Kernel::$currentRequestType !== "SOCKET")) {
            // Allow any HTML content type
            $contentType = HttpCache::$header["Content-Type"];
            if (false === strpos($contentType, "text/")) {
                $this->kernel->logger->debug('Content-Type of response is not HTML. Skipping Prophiler toolbar generation.');
                return $response;
            }
            $body = $response->getBody();
            if (!$body->eof() && $body->isSeekable()) {
                $body->seek(0, SEEK_END);
            }
            $rendered = $toolbar->render();
            $body->write($rendered);
            $response->withBody($body);
        }
        // socketio mode
        else {
            $rendered = $toolbar->render();
            $generatedStates = (array) json_decode((string) $response->getBody());
            $generatedStates["debugBar"] = $rendered;

            $stream = $this->kernel->factories->getStreamFactory()->createStream();
            $toWrite = json_encode((array) $generatedStates);

            // var_dump($toWrite);

            $stream->write($toWrite);
            $response = $response->withBody($stream);
        }
        return $response;
    }
}
