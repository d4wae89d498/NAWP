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
use Jasny\HttpMessage\ServerRequest;
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
                        ->withServerParams(['REQUEST_METHOD' => $this->params[0], "REQUEST_URI" => $this->params[1]]);
                case "Zend\Diactoros\Request":
                    return new $this->implementationName($this->params[1], $this->params[0]);
                default:
                    return new $this->implementationName($this->params[0], $this->params[1]);
            }
        });
        $instance = $this->create();
        if (!$instance instanceof RequestInterface) {
            throw new InvalidImplementation();
        } else {
            return $instance;
        }
    }
}
