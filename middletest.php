<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 10/30/2018
 * Time: 7:07 PM
 */
require_once "vendor/autoload.php";

$requestHandler = new class() implements \Psr\Http\Server\RequestHandlerInterface {
    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        // TODO: Implement handle() method.
        $response = new \Jasny\HttpMessage\Response();
        $stream = new \Jasny\HttpMessage\Stream();
        $stream->write("HELLO WORLD" . $request->getUri());
        $response = $response->withBody($stream);
        return $response;
    }
};

$middleware = new class() implements \Psr\Http\Server\MiddlewareInterface {
    public function process(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Server\RequestHandlerInterface $handler): \Psr\Http\Message\ResponseInterface
    {
        // TODO: Implement process() method.
        $request = $request->withUri(new Jasny\HttpMessage\Uri("/test23"));
        return $handler->handle($request);
    }
};

$dispatcher = new \Ellipse\Dispatcher($requestHandler, [$middleware]);

$request = (new \Jasny\HttpMessage\ServerRequest());
$request = $request->withUri(new Jasny\HttpMessage\Uri("aaa"));
$response = $dispatcher->handle($request);

var_dump((string) $response->getBody());