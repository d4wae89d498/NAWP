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
use GuzzleHttp\Psr7\LazyOpenStream;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

class StreamFactory extends Factory implements StreamFactoryInterface
{
    /**
     * @param string $content
     * @return StreamInterface
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     */
    public function createStream(string $content = null): StreamInterface
    {
        $this->params = [$content];
        $instance = $this->create();
        if (!$instance instanceof StreamInterface) {
            throw new InvalidImplementation();
        } else {
            return $instance;
        }
    }

    /**
     * @param string $filename
     * @param string $mode
     * @return StreamInterface
     * @throws InvalidImplementation
     */
    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {
        $this->params = [fopen($filename, $mode)];
        $this->setConstructor(function () use($filename, $mode){
            $split = explode("\\", $this->implementationName);
            if (isset($split[0]) && ($split[0] === "\\")) {
                unset($split[0]);
            }
            $implementationBase = join("\\", $split);
            switch ($implementationBase) {
                case  "GuzzleHttp\Psr7\stream_for" :
                    /**
                     * @var StreamInterface $instance
                     */
                    $instance = new LazyOpenStream($filename, $mode);
                    return $instance;
                default :
                    return new $this->implementationName( ... $this->params);
            };
        });
        $instance = $this->create();
        if (!$instance instanceof StreamInterface) {
            throw new InvalidImplementation();
        } else {
            return $instance;
        }
    }

    /**
     * @param resource $resource
     * @return StreamInterface
     * @throws InvalidImplementation
     */
    public function createStreamFromResource($resource): StreamInterface
    {
        $this->params = [$resource];
        $instance = $this->create();
        if (!$instance instanceof StreamInterface) {
            throw new InvalidImplementation();
        } else {
            return $instance;
        }
    }
}