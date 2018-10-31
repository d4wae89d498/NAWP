<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 10/31/2018
 * Time: 6:01 PM
 */

namespace App\Ipolitic\Nawpcore\Factories;


use App\Ipolitic\Nawpcore\Components\Factory;
use App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

class UriFactory extends Factory implements UriFactoryInterface
{
    /**
     * @param string $uri
     * @return UriInterface
     * @throws InvalidImplementation
     */
    public function createUri(string $uri = ''): UriInterface
    {
        $this->params = [$uri];
        $instance = $this->create();
        if (!$instance instanceof UriInterface) {
            throw new InvalidImplementation();
        } else {
            return $instance;
        }
    }
}