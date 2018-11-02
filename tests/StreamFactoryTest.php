<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 11/2/2018
 * Time: 11:33 AM
 */

namespace App\Tests;

use App\Ipolitic\Nawpcore\Factories\StreamFactory;
use GuzzleHttp\Psr7\Stream;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;

class StreamFactoryTest extends TestCase
{
    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \Exception
     */
    public function testCreateGuzzleStream()
    {
        $streamFactory = new StreamFactory(Stream::class);
        $r = $streamFactory->createStream();
        $this->assertInstanceOf(StreamInterface::class, $r);
    }

    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \Exception
     */
    public function testCreateZendStream()
    {
        $streamFactory = new StreamFactory(\Zend\Diactoros\Stream::class);
        $r = $streamFactory->createStream('php://memory');
        $this->assertInstanceOf(StreamInterface::class, $r);
    }

    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \Exception
     */
    public function testCreateJasnyStream()
    {
        $streamFactory = new StreamFactory(\Jasny\HttpMessage\Stream::class);
        $r = $streamFactory->createStream();
        $this->assertInstanceOf(StreamInterface::class, $r);
    }

    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \Exception
     */
    public function testCreateGuzzleStreamFromFile()
    {
        $streamFactory = new StreamFactory(Stream::class);
        $r = $streamFactory->createStreamFromFile(__FILE__, "r");
        $this->assertInstanceOf(StreamInterface::class, $r);
    }

    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \Exception
     */
    public function testCreateZendStreamFromFile()
    {
        $streamFactory = new StreamFactory(\Zend\Diactoros\Stream::class);
        $r = $streamFactory->createStreamFromFile(__FILE__, "r");
        $this->assertInstanceOf(StreamInterface::class, $r);
    }

    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \Exception
     */
    public function testCreateJasnyStreamFromFile()
    {
        $streamFactory = new StreamFactory(\Jasny\HttpMessage\Stream::class);
        $r = $streamFactory->createStreamFromFile(__FILE__, "r");
        $this->assertInstanceOf(StreamInterface::class, $r);
    }

    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \Exception
     */
    public function testCreateGuzzleStreamFromResource()
    {
        $streamFactory = new StreamFactory(Stream::class);
        $r = $streamFactory->createStreamFromResource(fopen(__FILE__, "r"));
        $this->assertInstanceOf(StreamInterface::class, $r);
    }

    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \Exception
     */
    public function testCreateZendStreamFromResource()
    {
        $streamFactory = new StreamFactory(\Zend\Diactoros\Stream::class);
        $r = $streamFactory->createStreamFromResource(fopen(__FILE__, "r"));
        $this->assertInstanceOf(StreamInterface::class, $r);
    }

    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \Exception
     */
    public function testCreateJasnyStreamFromResource()
    {
        $streamFactory = new StreamFactory(\Jasny\HttpMessage\Stream::class);
        $r = $streamFactory->createStreamFromResource(fopen(__FILE__, "r"));
        $this->assertInstanceOf(StreamInterface::class, $r);
    }
}