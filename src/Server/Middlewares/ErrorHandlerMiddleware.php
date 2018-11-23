<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 11/2/2018
 * Time: 2:36 PM
 */

namespace App\Server\Middlewares;

use App\Ipolitic\Nawpcore\Components\Middleware;
use App\Ipolitic\Nawpcore\Kernel;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Whoops\Exception\Frame;
use Whoops\Exception\Inspector;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

/**
 * Class ProfilerMiddleware
 * @package App\Server\Middlewares
 */
class ErrorHandlerMiddleware extends Middleware implements MiddlewareInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \Exception
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /**
         * Loadubg whoops integration
         */
        $run     = new Run();
        $errorHandler = new PrettyPageHandler();
        $errorHandler->handleUnconditionally(true);
        $errorHandler->setApplicationPaths([__FILE__]);
        $errorHandler->addDataTableCallback('Details', function (\Whoops\Exception\Inspector $inspector) {
            $data = array();
            $exception = $inspector->getException();
            $data['Exception class'] = get_class($exception);
            $data['Exception code'] = $exception->getCode();
            return $data;
        });
        $errorHandler->setPageTitle("NAWP Exception!");

        $run->pushHandler($errorHandler);
        // Example: tag all frames inside a function with their function name
        $run->pushHandler(function ($exception, Inspector $inspector, $run) {
            $inspector->getFrames()->map(function (Frame $frame) {
                if ($function = $frame->getFunction()) {
                    $frame->addComment("This frame is within function '$function'", 'cpt-obvious');
                }
                return $frame;
            });
        });
        $run->writeToOutput(false);
        $run->allowQuit(false);
        $run->register();

        /**
         * Handling page
         * @var ResponseInterface $response
         */
        $response = $this->kernel->factories->getResponseFactory()->createResponse();
        try {
            $response = $handler->handle($request);
            // throw new Exception("SOMEWHAT HAPPEND");
        } catch (\Exception | \Throwable $es) {
            foreach ($_ENV as $k => $v) {
                unset($_ENV[$k]);
            }
            $html = "";
            if ($es instanceof \Exception) {
                $this->kernel->logger->alert("Exception ! " . $es->getMessage() . " " . $es->getFile() . ":" . $es->getLine());
                $html = $run->handleException($es);
            } else {
                $this->kernel->logger->emergency("Error ! " . $es->getMessage() . " " . $es->getFile() . ":" . $es->getLine());
                $html = $run->handleError(E_ERROR, $es->getMessage());
            }
            // in http mode
            if ((Kernel::$currentRequestType !== "SOCKET")) {
                $stream = $this->kernel->factories->getStreamFactory()->createStream();
                $stream->write($html);
                $response = $response->withBody($stream);
            }
            // socketio mode
            else {
                $generatedStates = (array) json_decode((string) $response->getBody());
                $generatedStates["error"] = $html;

                $stream = $this->kernel->factories->getStreamFactory()->createStream();
                $toWrite = json_encode((array) $generatedStates);
                $stream->write($toWrite);
                $response = $response->withBody($stream);
            }
            return $response;
        } finally {
            return $response;
        }
    }
}
