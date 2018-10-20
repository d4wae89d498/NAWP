<?php declare(strict_type=1);
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/6/2018
 * Time: 1:09 PM
 */
namespace App\Controllers;

use App\iPolitic\NawpCore\Components\View;
use App\iPolitic\NawpCore\Components\ViewLogger;
use App\iPolitic\NawpCore\Components\Controller;
use App\iPolitic\NawpCore\Interfaces\ControllerInterface;
use  App\iPolitic\NawpCore\Components\Session as CSession;
use App\iPolitic\NawpCore\Components\PacketAdapter;

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
                "priority"  => 9,
            ],
            [
                "method"    => "home",
                "router"    => ["GET", "/"],
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
     * @throws \Exception
     */
    public function sessionsMiddleware(ViewLogger &$viewLogger, string &$httpResponse, array $args = []): bool
    {

        //die("session tick");
        CSession::tick($viewLogger);
        $httpResponse .= (CSession::isset($viewLogger, "TEST") ? CSession::get($viewLogger, "TEST") : "") . " " . CSession::id($viewLogger);
        CSession::set($viewLogger, "TEST", "Pomme");
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
        $httpResponse =
        new \App\Views\Elements\Header($viewLogger, []) .
        new \App\Views\Pages\Home($viewLogger, ["name" => "test", "html_elements" => [
                new \App\Views\Elements\Menu($viewLogger, []),
                    new \App\Views\Elements\Banner($viewLogger, []),
                    new \App\Views\Elements\BannerBlocks($viewLogger, []),
                    new \App\Views\Elements\Services($viewLogger, []),
                    new \App\Views\Elements\Gallery($viewLogger, []),
                    new \App\Views\Elements\OrderNow($viewLogger, []),
                    new \App\Views\Elements\Testimonial($viewLogger, []),
                    new \App\Views\Elements\Map($viewLogger, []),
                    new \App\Views\Elements\BlogWrapper($viewLogger, []),
        ]]) .
        new \App\Views\Elements\Footer($viewLogger, []);

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
        $httpResponse .= " ERROR 404";
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
        $httpResponse .= " ERROR 404";
        return true;
    }
}
