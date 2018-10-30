<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 10/30/2018
 * Time: 2:38 PM
 */

namespace App\Server\Middlewares;


use App\Ipolitic\Nawpcore\Components\Middleware;
use App\Ipolitic\Nawpcore\Kernel;
use Jasny\HttpMessage\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Controller  extends Middleware implements MiddlewareInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \iPolitic\Solex\RouterException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        Kernel::$kernel->handle(
            $request,
            $response,
            Kernel::$currentRequestType,
            Kernel::$currentPacket,
            Kernel::$kernel->rawTwig
        );
        return $response;
    }
}