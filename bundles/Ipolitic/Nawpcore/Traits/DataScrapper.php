<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 20/11/18
 * Time: 14:12
 */

namespace App\Ipolitic\Nawpcore\Traits;

use App\Server\PsrFactories;

/**
 * Trait DataScrapper (usable in kernel only)
 * @package App\Ipolitic\Nawpcore\Components
 */
trait DataScrapper
{
    /**
     * @param string $what
     * @param array $params
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     */
    public function get(string $what, array $params = []) {
        /**
         * @var PsrFactories $cacheFactory
         */
        var_dump($what);
        var_dump($params);
      //  $cache = $cacheFactory->createCache();
      //  var_dump("YAY");
    }
}