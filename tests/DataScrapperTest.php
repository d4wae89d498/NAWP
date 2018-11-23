<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 21/11/18
 * Time: 12:12
 */
namespace App\Tests;

use App\Ipolitic\Nawpcore\DataScrapper\WikipediaSearch;
use App\Ipolitic\Nawpcore\DataScrapper\WikipediaSearchResults;
use App\Ipolitic\Nawpcore\Kernel;
use PHPUnit\Framework\TestCase;

class DataScrapperTest /*extends TestCase*/
{
    public function testGet() : void
    {
        $search = new WikipediaSearch("TEST");
        var_dump($search->fetch(1)->get(".firstHeading")->toArray());
    }
}