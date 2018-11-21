<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 21/11/18
 * Time: 12:12
 */

namespace App\Tests;


use App\Ipolitic\Nawpcore\Kernel;
use PHPUnit\Framework\TestCase;

class DataScrapperTest extends TestCase
{
    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testGet() : void
    {
        $kernel = new Kernel();
        $cache = $kernel->factories->getCacheFactory()->createCache();
        $kernel->get("POMME");
        $this->assertTrue(true);
    }
}