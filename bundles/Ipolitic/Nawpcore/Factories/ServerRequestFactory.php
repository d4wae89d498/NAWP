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
use Jasny\HttpMessage\ServerRequest;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ServerRequestFactory
 * @package App\Ipolitic\Nawpcore\Factories
 */
class ServerRequestFactory extends Factory implements ServerRequestFactoryInterface
{
    /**
     * @param string $method
     * @param \Psr\Http\Message\UriInterface|string $uri
     * @param array $serverParams
     * @return ServerRequestInterface
     * @throws InvalidImplementation
     */
    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        $this->params = [$method, $uri, $serverParams];
        $this->setConstructor(function () {
            $split = str_split( $s = $this->implementationName);
            if (isset($split[0]) && ($split[0] === "\\")) {
                unset($split[0]);
            }
            $implementationBase = join("\\", $split);
            switch ($implementationBase) {
                case  "Jasny\HttpMessage\ServerRequest" :
                    /**
                     * @var ServerRequest $instance
                     */
                    $instance = new $s();
                    return $instance
                        ->withServerParams($this->params[2])
                        ->withServerParams(['REQUEST_METHOD' => $this->params[0], "REQUEST_URI" => $this->params[1]]);
                default :
                    return new $s();
            };

        });
        $instance = $this->create();
        if (!$instance instanceof ServerRequestInterface) {
            throw new InvalidImplementation();
        } else {
            return $instance;
        }
    }
}