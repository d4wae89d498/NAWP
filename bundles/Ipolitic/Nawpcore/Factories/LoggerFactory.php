<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 10/31/2018
 * Time: 5:34 PM
 */

namespace App\Ipolitic\Nawpcore\Factories;


use App\Ipolitic\Nawpcore\Components\Factory;
use App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation;
use App\Ipolitic\Nawpcore\Interfaces\LoggerFactoryInterface;
use Psr\Log\LoggerInterface;

class LoggerFactory extends Factory implements LoggerFactoryInterface
{
    /**
     * @return LoggerInterface
     * @throws InvalidImplementation
     */
    public function createLogger(): LoggerInterface {
        $instance = $this->create();
        if ($instance instanceof LoggerInterface) {
            return $instance;
        } else {
            throw new InvalidImplementation();
        }
    }
}