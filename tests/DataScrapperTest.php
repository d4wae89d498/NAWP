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

class DataScrapperTest
{
    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testGet() : void
    {
        $kernel = new Kernel();
         $searchResults = (new WikipediaSearchResults("pomme de terre"))->fill()->fetch();

    }
}