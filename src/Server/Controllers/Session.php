<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/6/2018
 * Time: 1:09 PM
 */
namespace App\Controllers;

use App\Ipolitic\Nawpcore\Components\View;
use App\Ipolitic\Nawpcore\Components\ViewLogger;
use App\Ipolitic\Nawpcore\Components\Controller;
use App\Ipolitic\Nawpcore\Interfaces\ControllerInterface;
use  App\Ipolitic\Nawpcore\Components\Session as CSession;
use App\Ipolitic\Nawpcore\Components\PacketAdapter;

/**
 * Class Sample
 * @package App\Controllers
 */
class Session extends Controller implements ControllerInterface
{

    /**
     * Describes controller methods
     * @return array
     */
    public function getMethods(): array
    {
        return
        [
            [
                "method"    => "sessionsMiddleware",
                "router"    => ["*", "*"],
                "priority"  => 999,
            ],
            [
                "method"    => "home",
                "router"    => ["*", "/"],
                "priority"  => 0,
            ],
            [
                 "method"    => "notFound",
                 "router"    => ["*", "*"],
                 "priority"  => -1,
            ],
            [
                 "method"    => "socketNotFound",
                 "router"    => ["SOCKET", "*"],
                 "priority"  => -0.5,
            ]
        ];
    }

    /**
     * @param ViewLogger $viewLogger
     * @param string $httpResponse
     * @param array $args
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function sessionsMiddleware(ViewLogger &$viewLogger, string &$httpResponse, array $args = []): bool
    {
        $viewLogger->sessionInstance->set("TEST", "Pomme");
        return false;
    }

    /**
     * display the homepage
     * @param ViewLogger $viewLogger
     * @param string $httpResponse
     * @param array $args
     * @return bool
     */
    public function home(ViewLogger &$viewLogger, string &$httpResponse, array $args = []): bool
    {
        $httpResponse = "<!DOCTYPE html><html lang=\"en\">" .
            new \App\Views\Elements\Header(
                $viewLogger,
                $this->logger,
                ["page" => "Login", "title" => "TEST".rand(0, 99), "url" => $_SERVER["REQUEST_URI"],
                    "cookies" => base64_encode(json_encode($viewLogger->cookies))]
            ) .
            "<body>" .
            new \App\Views\Pages\Page(

                $viewLogger,
                $this->logger,
                [
                    "pass" => isset($_POST["password"]) ? $_POST["password"] : "emptypass!",
                    "html_elements" => [
                        new \App\Views\Elements\Menu(
                            $viewLogger,
                            $this->logger,
                            [

                            ]
                        ),
                        new \App\Views\Elements\Carousel(
                            $viewLogger,
                            $this->logger,
                            [

                            ]
                        ),
                        new \App\Views\Elements\Marketing(
                            $viewLogger,
                            $this->logger,
                            [

                            ]
                        ),
                    ],
                ]
            ) .
            new \App\Views\Elements\Footer($viewLogger, $this->logger, [])
            .
            "</body></html>";
        return true;
    }

    /**
     * @param ViewLogger $viewLogger
     * @param string $httpResponse
     * @param array $args
     * @return bool
     */
    public function notFound(ViewLogger &$viewLogger, string &$httpResponse, array $args = []): bool
    {
        // $httpResponse .= " ERROR 404";
        return true;
    }

    /**
     * return a socket 404 packet
     * @param ViewLogger $viewLogger
     * @param string $httpResponse
     * @param array $args
     * @return bool
     */
    public function socketNotFound(ViewLogger &$viewLogger, string &$httpResponse, array $args = []): bool
    {
        // $httpResponse .= " ERROR 404";
        return true;
    }
}
