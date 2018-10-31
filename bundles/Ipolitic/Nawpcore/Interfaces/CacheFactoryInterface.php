<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 10/31/2018
 * Time: 6:02 PM
 */

namespace App\Ipolitic\Nawpcore\Interfaces;


use Psr\SimpleCache\CacheInterface;

interface CacheFactoryInterface
{
    public function createCache(): CacheInterface;
}