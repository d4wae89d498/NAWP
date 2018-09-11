<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/6/2018
 * Time: 1:09 PM
 */
namespace App\Controllers;

use App\iPolitic\NawpCore\Components\ViewLogger;
use App\iPolitic\NawpCore\Components\Controller;
use App\iPolitic\NawpCore\Interfaces\ControllerInterface;
use  App\iPolitic\NawpCore\Components\Session as CSession;

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
    public function getMethods(): array { return
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

    public function sessionsMiddleware(string &$httpResponse, array $args = [], string $requestType = self::DEFAULT_REQUEST_TYPE): bool {

        //die("session tick");
        CSession::tick();
        $httpResponse .= (CSession::isset("TEST") ? CSession::get("TEST") : "") . " " . CSession::id();
        CSession::set("TEST", "Pomme");
        return false;
    }

    /**
     * display the homepage
     * @param string $httpResponse
     * @param array $args
     * @return bool
     */
    public function home(string &$httpResponse, array $args = [], string $requestType = self::DEFAULT_REQUEST_TYPE): bool {
        $templateLogger = new ViewLogger();
        $httpResponse .= new \App\Views\Pages\Home($templateLogger, ["name" => "test", "elements" => [
            new \App\Views\Elements\Header($templateLogger, []),
                new \App\Views\Elements\Menu($templateLogger, []),
                    new \App\Views\Elements\Banner($templateLogger, []),
                    new \App\Views\Elements\BannerBlocks($templateLogger, []),
                    new \App\Views\Elements\Services($templateLogger, []),
                    new \App\Views\Elements\Gallery($templateLogger, []),
                    new \App\Views\Elements\OrderNow($templateLogger, []),
                    new \App\Views\Elements\Testimonial($templateLogger, []),
                    new \App\Views\Elements\Map($templateLogger, []),
                    new \App\Views\Elements\BlogWrapper($templateLogger, []),
            new \App\Views\Elements\Footer($templateLogger, []),
        ]]);
        return true;
    }

    /**
     * return a http 404 page
     * @param string $httpResponse
     * @param array $args
     * @return bool
     */
    public function notFound(string &$httpResponse, array $args = [], string $requestType = self::DEFAULT_REQUEST_TYPE): bool {
        $httpResponse .= " ERROR 404";
        return true;
    }

    /**
     * return a socket 404 packet
     * @param string $httpResponse
     * @param array $args
     * @return bool
     */
    public function socketNotFound(string &$httpResponse, array $args = [], string $requestType = self::DEFAULT_REQUEST_TYPE): bool {
        $httpResponse .= " ERROR 404";
        return true;
    }
}
