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
use  App\iPolitic\NawpCore\Components\Session as SupSession;

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


    public function sessionsMiddleware(string &$httpResponse, $args = []): bool {
        //die("session tick");
        SupSession::tick();
        $token = SupSession::id();
        $httpResponse .= (SupSession::isset($token, "TEST") ? SupSession::get($token, "TEST") : "") . " " . SupSession::id();
        SupSession::set($token, "TEST", "Pomme");
        return false;
    }

    /**
     * display a homepage
     * @param string $httpResponse
     * @param array $args
     * @return bool
     */
    public function home(string &$httpResponse, $args = []): bool {
        $templateLogger = new ViewLogger();
        $httpResponse .= new \App\Views\Pages\Home($templateLogger, ["name" => "test"]);
        return true;
    }

    /**
     * return a http 404 page
     * @param string $httpResponse
     * @param array $args
     * @return bool
     */
    public function notFound(string &$httpResponse, $args = []): bool {
        $httpResponse.= "404";
        return true;
    }

    /**
     * return a socket 404 packet
     * @param string $httpResponse
     * @param array $args
     * @return bool
     */
    public function socketNotFound(string &$httpResponse, $args = []): bool {
        $httpResponse .= "404";
        return true;
    }
}
