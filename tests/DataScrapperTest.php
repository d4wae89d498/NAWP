<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 21/11/18
 * Time: 12:12
 */
namespace App\Tests;

use App\Ipolitic\Nawpcore\DataScrapper\WikipediaSearchResults;
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
        $searchResults = (new WikipediaSearchResults("pomme de terre"))->fill()->fetch();
        var_dump($searchResults->allLinks);
        $this->assertTrue(true);

    }
}