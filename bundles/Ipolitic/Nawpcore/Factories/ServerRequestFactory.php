<?php declare(strict_types=1);
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
            $split = explode("\\", $this->implementationName);
            if (isset($split[0]) && ($split[0] === "\\")) {
                unset($split[0]);
            }
            $implementationBase = join("\\", $split);
            switch ($implementationBase) {
                case  "Jasny\HttpMessage\ServerRequest":
                    /**
                     * @var ServerRequest $instance
                     */
                    $instance = new $this->implementationName();
                    return $instance
                        ->withServerParams($this->params[2])
                        ->withServerParams(['REQUEST_METHOD' => $this->params[0], "REQUEST_URI" => $this->params[1]]);
                case "Zend\Diactoros\ServerRequest":
                    return new $this->implementationName($this->params[2], [], $this->params[1], $this->params[0]);
                case "GuzzleHttp\Psr7\ServerRequest":
                    return new $this->implementationName($this->params[0], $this->params[1], [], null, '1.1', $this->params[2]);
                default:
                    return new $this->implementationName();
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
