<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 11/2/2018
 * Time: 11:29 AM
 */

namespace App\Tests;


use App\Ipolitic\Nawpcore\Components\RequestHandler;
use App\Ipolitic\Nawpcore\Factories\RequestHandlerFactory;
use App\Ipolitic\Nawpcore\Kernel;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;

class RequestHandlerFactoryTest extends TestCase
{
    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    public function testNawpRequestHandler() : void
    {
        $kernel = new Kernel();
        $requestHandlerFactory = new RequestHandlerFactory(RequestHandler::class);
        $requestHandlerFactory->setConstructor(function() use ($requestHandlerFactory, &$kernel){
            return new $requestHandlerFactory->implementationName($kernel, "GET", null);
        });
        $requestHandlerFactory = $requestHandlerFactory->createRequestHandler();
        $this->assertInstanceOf(RequestHandlerInterface::class, $requestHandlerFactory);
    }
}