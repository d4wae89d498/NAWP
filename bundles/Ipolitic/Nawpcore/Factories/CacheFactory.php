<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 10/31/2018
 * Time: 6:00 PM
 */

namespace App\Ipolitic\Nawpcore\Factories;

use App\Ipolitic\Nawpcore\Components\Factory;
use App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation;
use Psr\SimpleCache\CacheInterface;

class CacheFactory extends Factory
{
    /**
     * @return CacheInterface
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     */
    public function createCache(): CacheInterface
    {
        $instance = $this->create();
        if ($instance instanceof  CacheInterface) {
            return $instance;
        } else {
            throw new InvalidImplementation();
        }
    }
}
