<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 10/31/2018
 * Time: 6:04 PM
 */

namespace App\Ipolitic\Nawpcore\Components;

use App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation;
use Psr\SimpleCache\CacheInterface;


class Factory
{
    /**
     * @var string
     */
    public $implementationName;
    /**
     * @var array
     */
    public $params;
    /**
     * @var callable|null
     */
    public $constructorCallback = null;
    /**
     * @var callable|null
     */
    public $alterInstanceCallback = null;

    /**
     * Factory constructor.
     * @param string $implementation
     * @param array $params
     */
    public function __construct(string $implementation, array $params = [])
    {
        $this->params = $params;
        $this->implementationName = $implementation;
    }
    /**
     * @throws InvalidImplementation
     */
    public function create() {
        try {
            $instance = null;
            if ($this->constructorCallback === null) {
                $c = $this->implementationName;
                $instance = new $c( ... $this->params);
            } else {
                $method = $this->constructorCallback;
                $instance = $method();
            }
            if ($this->alterInstanceCallback !== null) {
                $method = $this->alterInstanceCallback;
                $method($instance);
            }

            return $instance;
        } catch (\Throwable $exception) {
            var_dump($exception);
            throw new InvalidImplementation("Invalid implementation given.");
        }
    }

    public function setConstructor($callable): void
    {
        $this->constructorCallback = $callable;
    }

    public function setAlter($callbable) {
        $this->alterInstanceCallback = $callbable;
    }
}