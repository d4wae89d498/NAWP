<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 10/31/2018
 * Time: 7:55 PM
 */

namespace App\Ipolitic\Nawpcore\Factories;


use App\Ipolitic\Nawpcore\Components\Factory;
use App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

class ResponseFactory extends Factory implements ResponseFactoryInterface
{
    /**
     * @param int $code
     * @param string $reasonPhrase
     * @return ResponseInterface
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     */
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        $this->params = [$code, $reasonPhrase];
        if (!strpos($this->implementationName, "GuzzleHttp\Psr7\Response")) {
            $this->setAlter(function (ResponseInterface &$instance) : void {
                if (isset($this->params[1]) && $this->params[1] !== '') {
                    $instance = $instance->withStatus($this->params[1], $this->params[1]);
                }
            });
        } else {
            $this->setConstructor(function() use ($code){
                if (strpos($this->implementationName, "Zend\Diactoros\Response")) {
                    return new $this->implementationName('php://memory',$code, []);
                } else {
                    return new $this->implementationName();
                }
            });
        }
        $instance = $this->create();
        if (!$instance instanceof ResponseInterface) {
            throw new InvalidImplementation();
        } else {
            return $instance;
        }
    }
}