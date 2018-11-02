<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 11/1/2018
 * Time: 10:24 AM
 */

namespace App\Tests;

use App\Ipolitic\Nawpcore\Components\Logger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class LoggerFactoryTest extends TestCase
{
    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testNawpLogger()
    {
        $requestFactory = new \App\Ipolitic\Nawpcore\Factories\LoggerFactory(Logger::class);
        $result = $requestFactory->createLogger();
        $this->assertInstanceOf(LoggerInterface::class, $result);
    }
}