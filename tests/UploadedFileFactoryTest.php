<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 11/2/2018
 * Time: 11:51 AM
 */

namespace App\Tests;


use App\Ipolitic\Nawpcore\Factories\UploadedFileFactory;
use Jasny\HttpMessage\Stream;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UploadedFileInterface;
use Zend\Diactoros\UploadedFile;

class UploadedFileFactoryTest extends TestCase
{
    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \Exception
     */
    public function testCreateZendUploadedFile() : void
    {
        $factory = new UploadedFileFactory(UploadedFile::class);
        $instance = $factory->createUploadedFile(new Stream());
        $this->assertInstanceOf(UploadedFileInterface::class, $instance);
    }
}