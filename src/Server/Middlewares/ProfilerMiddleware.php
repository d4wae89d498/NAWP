<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 11/2/2018
 * Time: 2:36 PM
 */

namespace App\Server\Middlewares;

use App\Ipolitic\Nawpcore\Components\Middleware;
use App\Ipolitic\Nawpcore\Components\ProfilerRequest;
use App\Ipolitic\Nawpcore\Kernel;
use Fabfuel\Prophiler\Profiler;
use Fabfuel\Prophiler\Toolbar;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

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

        $response = $handler->handle($request);
        // Allow any HTML content type
        $contentType = $response->getHeaderLine('Content-Type');
        if (!preg_match('#^(?:text/html|application/xhtml\+xml)\s*(?:;|$)#', $contentType) || (Kernel::$currentRequestType === "SOCKET")) {
            $this->kernel->logger->debug('Content-Type of response is not HTML. Skipping Prophiler toolbar generation.');
            return $response;
        }
        $toolbar->addDataCollector(new ProfilerRequest($request));
        $body = $response->getBody();
        if (!$body->eof() && $body->isSeekable()) {
            $body->seek(0, SEEK_END);
        }
        $body->write($toolbar->render());
        $response->withBody($body);
        return $response;
    }
}
