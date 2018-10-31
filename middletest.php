<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 10/30/2018
 * Time: 7:07 PM
 */
require_once "vendor/autoload.php";
try {

    $kernel         = new \App\Ipolitic\Nawpcore\Kernel();

    var_dump($kernel->factories->getLoggerFactory()->createLogger());
    var_dump($kernel->factories->getCacheFactory()->createCache());
    var_dump($kernel->factories->getServerFactory()->createServerRequest("GET", "/"));
    var_dump($kernel->factories->getResponseFactory()->createResponse());
    var_dump($kernel->factories->getStreamFactory()->createStream());
    var_dump($kernel->factories->getUploadedFileFactory()->createUploadedFile(new \Jasny\HttpMessage\Stream()));
    var_dump($kernel->factories->getRequestFactory()->createRequest("GET", "/"));


    $factorie = $kernel->factories->getRequestHandlerFactory();
    $factorie->setConstructor(function() use ($factorie, &$kernel){
        return new $factorie->implementationName($kernel, "GET", null);
    });

    var_dump($factorie->createRequestHandler());
} catch(Exception $ex) {
    var_dump($ex);
}