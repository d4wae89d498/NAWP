<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/5/2018
 * Time: 7:46 PM
 */
namespace App\Ipolitic\Nawpcore\Components;

use Atlas\Orm\Atlas;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * The controller class, convert requests to states rendered as a json
 * or as a standard html template with custom js
 */
class Controller implements LoggerAwareInterface
{
    /**
     * @var LoggerInterface
     */
    public $logger;
    /**
     * The controller name
     * @var string
     */
    public $name = "";
    /**
     * @var Atlas
     */
    public $atlas;

    /**
     * Controller constructor.
     * @param Atlas $atlas
     * @param LoggerInterface $logger
     */
    public function __construct(Atlas $atlas, LoggerInterface $logger)
    {
        $this->atlas = $atlas;
        $this->name = get_class($this);
        $this->setLogger($logger);
    }

    /**
     * Will call a controller method
     *
     * @param ViewLogger $viewLogger
     * @param string $response
     * @param string $method
     * @param array $args
     * @return bool
     *
     */
    public function call(ViewLogger &$viewLogger, string &$response, string $method, $args = []): bool
    {
        //var_dump("IN CALLL" . $method);
        if (method_exists($this, $method)) {
            return $this->$method($viewLogger, $response, $args);
        } else {
            return false;
        }
    }

    /**
     * @param LoggerInterface $logger
     * @return void
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
