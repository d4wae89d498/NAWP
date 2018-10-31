<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 10/31/2018
 * Time: 8:09 PM
 */

namespace App\Server;

use App\Ipolitic\Nawpcore\Components\Logger;
use App\Ipolitic\Nawpcore\Components\RequestHandler;
use App\Ipolitic\Nawpcore\Factories\CacheFactory;
use App\Ipolitic\Nawpcore\Factories\LoggerFactory;
use App\Ipolitic\Nawpcore\Factories\RequestFactory;
use App\Ipolitic\Nawpcore\Factories\RequestHandlerFactory;
use App\Ipolitic\Nawpcore\Factories\ResponseFactory;
use App\Ipolitic\Nawpcore\Factories\ServerRequestFactory;
use App\Ipolitic\Nawpcore\Factories\StreamFactory;
use App\Ipolitic\Nawpcore\Factories\UploadedFileFactory;
use App\Ipolitic\Nawpcore\Factories\UriFactory;
use App\Ipolitic\Nawpcore\Kernel;
use Jasny\HttpMessage\Response;
use Jasny\HttpMessage\ServerRequest;
use Jasny\HttpMessage\Stream;
use Zend\Diactoros\UploadedFile;
use Jasny\HttpMessage\Uri;
use Symfony\Component\Cache\Simple\FilesystemCache;

class PsrFactories
{
    public $factories = [];
    public function __construct(Kernel $kernel)
    {
        $this->factories = [
            "logger"         => new LoggerFactory           (Logger::class),
            "cache"          => (new CacheFactory           (FilesystemCache::class, [
                '', 0, join(DIRECTORY_SEPARATOR, [$kernel->cachePath, "packetAdapter"]),
            ])),
            "request"        => new RequestFactory           (ServerRequest::class),
            "server"         => new ServerRequestFactory     (ServerRequest::class),
            "response"       => new ResponseFactory          (Response::class),
            "stream"         => new StreamFactory            (Stream::class),
            "uploadedFile"   => new UploadedFileFactory      (UploadedFile::class),
            "uri"            => new UriFactory               (Uri::class),
            "requestHandler" => new RequestHandlerFactory    (RequestHandler::class),
        ];
    }
    public function getLoggerFactory(): LoggerFactory
    {return $this->factories["logger"];}
    public function getCacheFactory(): CacheFactory
    {return $this->factories["cache"];}
    public function getRequestFactory(): RequestFactory
    {return $this->factories["request"];}
    public function getServerFactory(): ServerRequestFactory
    {return $this->factories["server"];}
    public function getResponseFactory(): ResponseFactory
    {return $this->factories["response"];}
    public function getStreamFactory(): StreamFactory
    {return $this->factories["stream"];}
    public function getUploadedFileFactory(): UploadedFileFactory
    {return $this->factories["uploadedFile"];}
    public function getUriFactory(): UriFactory
    {return $this->factories["uri"];}
    public function getRequestHandlerFactory(): RequestHandlerFactory
    {return $this->factories["requestHandler"];}
}