<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 11/2/2018
 * Time: 11:57 AM
 */

namespace App\Tests;


use App\Ipolitic\Nawpcore\Factories\ResponseFactory;
use App\Ipolitic\Nawpcore\Factories\StreamFactory;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ResponseFactoryTest extends TestCase
{
    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \Exception
     */
    public function testCreateGuzzleResponse() : void
    {
        $responseFactory = new ResponseFactory(Response::class);
        $response = $responseFactory->createResponse(200, "OK");
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('OK', $response->getReasonPhrase());
        $this->assertSame('1.1', $response->getProtocolVersion());
        $this->assertSame([], $response->getHeaders());
        $this->assertInstanceOf('Psr\Http\Message\StreamInterface', $response->getBody());
        $this->assertSame('', (string) $response->getBody());
        $sr = 'hello world';
        $streamFactory = new StreamFactory(Stream::class);
        $resource = $streamFactory->createStream($sr);
        $stream = $response->withBody($resource);
        $this->assertSame($sr, $stream->getBody()->getContents());
    }

    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \Exception
     */
    public function testNotFoundGuzzleResponse()
    {
        $responseFactory = new ResponseFactory(Response::class);
        $response = $responseFactory->createResponse(404);
        $this->assertSame(404, $response->getStatusCode());
        $this->assertSame('Not Found', $response->getReasonPhrase());
    }

    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \Exception
     */
    public function testCreateZendResponse()
    {
        $responseFactory = new ResponseFactory(\Zend\Diactoros\Response::class);
        $response = $responseFactory->createResponse(200, 'OK');
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('OK', $response->getReasonPhrase());
        $this->assertSame('1.1', $response->getProtocolVersion());
        $this->assertSame([], $response->getHeaders());
        $this->assertInstanceOf(StreamInterface::class, $response->getBody());
        $this->assertSame('', (string) $response->getBody());
        $streamFactory = new StreamFactory(\Zend\Diactoros\Stream::class);
        $resource = $streamFactory->createStream('php://memory');
        $stream = $response->withBody($resource);
        $this->assertSame('', $stream->getBody()->getContents());
    }

    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \Exception
     */
    public function testNotFoundZendResponse()
    {
        $responseFactory = new ResponseFactory(\Zend\Diactoros\Response::class);
        $r = $responseFactory->createResponse(404);
        $this->assertSame(404, $r->getStatusCode());
        $this->assertSame('Not Found', $r->getReasonPhrase());
    }

    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \Exception
     */
    public function testCreateJasnyResponse()
    {
        $responseFactory = new ResponseFactory(\Jasny\HttpMessage\Response::class);
        $response = $responseFactory->createResponse(200, 'OK');
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('OK', $response->getReasonPhrase());
        $this->assertSame('1.1', $response->getProtocolVersion());
        $this->assertSame([], $response->getHeaders());
        $this->assertInstanceOf(StreamInterface::class, $response->getBody());
        $this->assertSame('', (string) $response->getBody());
        $streamFactory = new StreamFactory(\Jasny\HttpMessage\Stream::class);
        $resource = $streamFactory->createStream();
        $stream = $response->withBody($resource);
        $this->assertSame('', $stream->getBody()->getContents());
    }

    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \Exception
     */
    public function testNotFoundJasnyResponse()
    {
        $responseFactory = new ResponseFactory(Response::class);
        $r = $responseFactory->createResponse(404);
        $this->assertSame(404, $r->getStatusCode());
        $this->assertSame('Not Found', $r->getReasonPhrase());
    }
}