<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 11/2/2018
 * Time: 11:55 AM
 */

namespace App\Tests;


use App\Ipolitic\Nawpcore\Factories\UriFactory;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;

class UriFactoryTest extends TestCase
{
    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \Exception
     */
    public function testGuzzleCreateUri() : void
    {
        $uriFactory = new UriFactory(Uri::class);
        $r = $uriFactory->createUri('/');
        $this->assertInstanceOf(UriInterface::class, $r);
    }

    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \Exception
     */
    public function testZendCreateUri()
    {
        $uriFactory = new UriFactory(\Zend\Diactoros\Uri::class);
        $r = $uriFactory->createUri('/');
        $this->assertInstanceOf(UriInterface::class, $r);
    }

    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \Exception
     */
    public function testJasnyCreateUri()
    {
        $uriFactory = new UriFactory(\Jasny\HttpMessage\Uri::class);
        $r = $uriFactory->createUri('/');
        $this->assertInstanceOf(UriInterface::class, $r);
    }
}