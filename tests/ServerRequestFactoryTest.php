<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 11/2/2018
 * Time: 11:24 AM
 */

namespace App\Tests;


use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\ServerRequest;

class ServerRequestFactoryTest extends TestCase
{
    /**
     * @throws \Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testCreateGuzzleServerRequest()
    {
        $requestFactory = new \App\Ipolitic\Nawpcore\Factories\ServerRequestFactory(\GuzzleHttp\Psr7\ServerRequest::class);
        $result = $requestFactory->createServerRequest('GET', 'http://localhost');
        $this->assertInstanceOf(ServerRequestInterface::class, $result);
        $this->assertEquals($result->getMethod(), 'GET');
        $this->assertEquals($result->getHeaders()['Host'][0], 'localhost');
    }

    /**
     * @throws \Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testCreateZendServerRequest()
    {
        $requestFactory = new \App\Ipolitic\Nawpcore\Factories\ServerRequestFactory(ServerRequest::class);
        $result = $requestFactory->createServerRequest('GET', 'http://localhost');
        $this->assertInstanceOf(ServerRequestInterface::class, $result);
        $this->assertEquals($result->getMethod(), 'GET');
    }

    /**
     * @throws \Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testCreateJasnyServerRequest()
    {
        $requestFactory = new \App\Ipolitic\Nawpcore\Factories\ServerRequestFactory(\Jasny\HttpMessage\ServerRequest::class);
        /**
         * @var ServerRequest $result
         */
        $result = $requestFactory->createServerRequest('GET', '/test');
        $this->assertInstanceOf(ServerRequestInterface::class, $result);
        $this->assertEquals($result->getServerParams()["REQUEST_METHOD"], 'GET');
        $this->assertEquals($result->getUri(), "/test");
    }
}