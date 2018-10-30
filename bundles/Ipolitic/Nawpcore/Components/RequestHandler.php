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
use Psr\Http\Server\RequestHandlerInterface;

class RequestHandler implements RequestHandlerInterface
{
    /**
     * @var Kernel
     */
    public $kernel;
    /**
     * @var ResponseInterface
     */
    public $response;
    /**
     * @var string
     */
    public $requestType = "GET";
    /**
     * @var null|Packet
     */
    public $packet = null;

    /**
     * RequestHandler constructor.
     * @param Kernel $kernel
     * @param ResponseInterface $response
     * @param string $requestType
     * @param null $packet
     */
    public function __construct(Kernel &$kernel, string $requestType, $packet = null)
    {
        $this->kernel = $kernel;
        $this->requestType = $requestType;
        Kernel::$currentPacket = $packet;
        Kernel::$currentRequestType = $requestType;
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
        $response = new Response();
        Kernel::$kernel->handle(
            $request,
            $response,
            Kernel::$currentRequestType,
            Kernel::$currentPacket,
            Kernel::$kernel->rawTwig
        );
        return $response;
    }
}
