<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 11/2/2018
 * Time: 11:18 AM
 */

namespace App\Tests;


use App\Ipolitic\Nawpcore\Factories\CacheFactory;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Simple\FilesystemCache;

class CacheFactoryTest extends TestCase
{
    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \Exception
     */
    public function testSymfonyCache() {
        $factory = new CacheFactory(FilesystemCache::class,
            ['', 0, join(DIRECTORY_SEPARATOR, ["./../cache", "session"])]);
        $cache = $factory->createCache();
        $this->assertInstanceOf(CacheInterface::class, $cache);
    }
}