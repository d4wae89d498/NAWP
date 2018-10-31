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
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UploadedFileInterface;

class UploadedFileFactory extends Factory implements UploadedFileFactoryInterface
{
    /**
     * @param StreamInterface $stream
     * @param int|null $size
     * @param int $error
     * @param string|null $clientFilename
     * @param string|null $clientMediaType
     * @return UploadedFileInterface
     * @throws InvalidImplementation
     */
    public function createUploadedFile(StreamInterface $stream,
                                       int $size = null,
                                       int $error = \UPLOAD_ERR_OK,
                                       string $clientFilename = null,
                                       string $clientMediaType = null): UploadedFileInterface
    {
        $size = $size === null ? $stream->getSize() : $size;
        $this->params = [$stream, $size, $error, $clientFilename, $clientMediaType];
        $instance = $this->create();
        if (!$instance instanceof UploadedFileInterface) {
            throw new InvalidImplementation();
        }  else {
            return $instance;
        }
    }
}