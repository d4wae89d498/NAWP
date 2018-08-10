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
/**
 * Class Sample
 * @package App\Controllers
 */
class Sample extends Controller implements ControllerInterface
{
    /*_________________________________________________
    |REQUEST TO CONTROLLER WINDOW          -   [ ]   X |
    |__________________________________________________|
    |*/ public function getMethods(): array { return [[/*======|
    |*- - - - Home method - - - - - - - - - - - - - - -|
    |*/     "method"    => "home",                    /*
    |*/     "router"    => ["GET", "*"],        /*
    |*/     "priority"  => 0,                         /*
    |                                                  |
    |*/],[/*- 404 method - - - - - - - - - - - - - - - | <- Riemann's critic line
    |*/     "method"    => "notFound",                /*
    |*/     "router"    => ["*", "*"],                /*
    |*/     "priority"  => -1,                        /*
    |                                                  |
    |*/],[/*- Socket demo - - - - - - - - - -  - - - - |
    |*/     "method"    => "socketNotFound",          /*
    |*/     "router"    => ["SOCKET", "*"],           /*
    |*/     "priority"  => -0.5,                     /* <- Riemann's zeros
    |*/],];}/*===================================== */


    /**
     * display a homepage
     * @param string $httpResponse
     * @param array $args
     * @return bool
     */
    public function home(string &$httpResponse, $args = []): bool {
        $templateLogger = new ViewLogger();
        $httpResponse = new \App\Views\Pages\Home($templateLogger, ["name" => "test"]);
        return true;
    }

    /**
     * return a http 404 page
     * @param string $httpResponse
     * @param array $args
     * @return bool
     */
    public function notFound(string &$httpResponse, $args = []): bool {
        $httpResponse = "404";
        return true;
    }

    /**
     * return a socket 404 packet
     * @param string $httpResponse
     * @param array $args
     * @return bool
     */
    public function socketNotFound(string &$httpResponse, $args = []): bool {
        $httpResponse = "404";
        return true;
    }
}
