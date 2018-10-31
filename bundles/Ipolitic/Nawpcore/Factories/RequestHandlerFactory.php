<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 10/31/2018
 * Time: 5:59 PM
 */

namespace App\Ipolitic\Nawpcore\Factories;

use App\Ipolitic\Nawpcore\Components\Factory;
use App\Ipolitic\Nawpcore\Components\RequestHandler;
use App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation;
use App\Ipolitic\Nawpcore\Interfaces\RequestHandlerFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestHandlerFactory extends Factory implements RequestHandlerFactoryInterface
{
    /**
     * @return RequestHandler
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     */
    public function createRequestHandler(): RequestHandlerInterface {
        $instance = $this->create();
        if ($instance instanceof  RequestHandler) {
            return $instance;
        } else {
            throw new InvalidImplementation();
        }
    }
}