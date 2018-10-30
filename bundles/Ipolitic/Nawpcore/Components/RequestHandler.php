<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 10/28/2018
 * Time: 2:06 PM
 */

namespace App\Ipolitic\Nawpcore\Components;

use App\Ipolitic\Nawpcore\Kernel;
use Jasny\HttpMessage\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RequestHandler
{
    public $kernel;
    public $requestType = "GET";
    public $packet = null;

    /**
     * RequestHandler constructor.
     * @param Kernel $kernel
     * @param string $requestType
     * @param null $packet
     */
    public function __construct(Kernel &$kernel, string $requestType, $packet = null)
    {
        $this->kernel = $kernel;
        $this->requestType = $requestType;
        $this->packet = $packet;
    }

    /**
     *  Handles a request and produces a response
     *
     * May call other collaborating code to generate the response.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \iPolitic\Solex\RouterException
     * @throws \Exception
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        //$psrResponse = (new Response())->withGlobalEnvironment(true);
        $response = (new Response());

        $this->kernel->handle(
            $request,
            $response,
            $this->requestType,
            $this->packet,
            $this->kernel->rawTwig
        );
        return $response;
    }
}
