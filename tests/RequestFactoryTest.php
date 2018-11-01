<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 11/1/2018
 * Time: 10:24 AM
 */

namespace App\Tests;

use Jasny\HttpMessage\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

class RequestFactoryTest extends TestCase
{
    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testCreateGuzzleRequest()
    {
        $requestFactory = new \App\Ipolitic\Nawpcore\Factories\RequestFactory(\Zend\Diactoros\Request::class);
        $result = $requestFactory->createRequest('GET', 'http://localhost');
        $this->assertInstanceOf(RequestInterface::class, $result);
        $this->assertEquals($result->getMethod(), 'GET');
        $this->assertEquals($result->getHeaders()['Host'][0], 'localhost');
    }

    /**
     * @throws \Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testCreateZendRequest()
    {
        $requestFactory = new \App\Ipolitic\Nawpcore\Factories\RequestFactory(\Zend\Diactoros\Request::class);
        $result = $requestFactory->createRequest('GET', 'http://localhost');
        $this->assertInstanceOf(RequestInterface::class, $result);
        $this->assertEquals($result->getMethod(), 'GET');
    }

    /**
     * @throws \Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testCreateJasnyRequest()
    {
        $requestFactory = new \App\Ipolitic\Nawpcore\Factories\RequestFactory(ServerRequest::class);
        /**
         * @var ServerRequest $result
         */
        $result = $requestFactory->createRequest('GET', '/test');
        $this->assertInstanceOf(RequestInterface::class, $result);
        $this->assertEquals($result->getMethod(), 'GET');
        $this->assertEquals($result->getUri(), "/test");
    }
}