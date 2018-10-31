<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 10/31/2018
 * Time: 6:00 PM
 */

namespace App\Ipolitic\Nawpcore\Factories;


use App\Ipolitic\Nawpcore\Components\Factory;
use App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;

class RequestFactory extends Factory implements RequestFactoryInterface
{
    /**
     * @param string $method
     * @param \Psr\Http\Message\UriInterface|string $uri
     * @return RequestInterface
     * @throws InvalidImplementation
     */
    public function createRequest(string $method, $uri) : RequestInterface
    {
        $this->params = [$method, $uri];
        $instance = $this->create();
        if (!$instance instanceof RequestInterface) {
            throw new InvalidImplementation();
        } else {
            return $instance;
        }
    }
}