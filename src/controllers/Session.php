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
use  \iPolitic\NawpCore\components\Session as SupSession;
use Workerman\Protocols\Http;

/**
 * Class Sample
 * @package App\Controllers
 */
class Session extends Controller implements ControllerInterface
{

    /*_________________________________________________
    |REQUEST TO CONTROLLER WINDOW          -   [ ]   X |
    |__________________________________________________|
    |*/ public function getMethods(): array { return [
    [/*- 404 method - - - - - - - - - - - - - - -      |
    |*/     "method"    => "sessionsMiddleware",      /*
    |*/     "router"    => ["*", "*"],                /*
    |*/     "priority"  => 99,                        /*
    |                                                  |
    |*/],[/*======|
    |*- - - - Home method - - - - - - - - - - - - - - -|
    |*/     "method"    => "home",                    /*
    |*/     "router"    => ["GET", "/"],        /*
    |*/     "priority"  => 0,                         /*
    |                                                  |
    |*/],[/*- 404 method - - - - - - - - - - - - - - - | <- critic line
    |*/     "method"    => "notFound",                /*
    |*/     "router"    => ["*", "*"],                /*
    |*/     "priority"  => -1,                        /*
    |                                                  |
    |*/],[/*- Socket demo - - - - - - - - - -  - - - - |
    |*/     "method"    => "socketNotFound",          /*
    |*/     "router"    => ["SOCKET", "*"],           /*
    |*/     "priority"  => -0.5,                      /*
    |*/],[/*- Socket demo - - - - - - - - - -  - - - - |
    |*/     "method"    => "admin",          /*
    |*/     "router"    => ["*", "/admin"],           /*
    |*/     "priority"  => -0.5,                     /* <- zeros
    |*/]];}/*===================================== */


    public function sessionsMiddleware(string &$httpResponse, $args = []): bool {
        /**
         * If a prime number is generated, we check for token expirity
         */
        $a = 0; if(call_user_func_array(function ($n)use(&$a){for($i=~-$n**.5|0;$i&&$n%$i--;);return!$i&$n>2|$n==2; }, [$a = mt_rand()])) {
            echo "PRIME GENERATED : " . $a;
            SupSession::tokenExpireCheck();
        }
        $token = SupSession::getVisitorToken();
        if(!SupSession::sessionIsloggedIn($token)) {
            SupSession::sessionLogIn($token);
        }

        $httpResponse .= (SupSession::sessionIsset($token, "TEST") ? SupSession::sessionGet($token, "TEST") : "") . " " . SupSession::getVisitorToken();
        SupSession::sessionSet($token, "TEST", "Pomme");
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

    /**
     * return a http admin page
     * @param string $httpResponse
     * @param array $args
     * @return bool
     */
    public function admin(string &$httpResponse, $args = []): bool {
        $httpResponse .= "";
        return true;
    }
}
