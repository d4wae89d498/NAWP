<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 8/6/2018
 * Time: 1:09 PM
 */
namespace App\Controllers;

use App\iPolitic\NawpCore\Controller;

class Sample extends Controller
{
        /*_________________________________________________
        |REQUEST TO CONTROLLER WINDOW        | - | [ ] | X |
         __________________________________________________|
        |*/ public $methods = [[/*=========================|
        |*- - - Home method - - - - - - - - - - - - - - - -|/
        |*/     "method" => "home",                       /*/
        |*/     "router" => ["*", "/:page"],              /*/
        |*/     "priority" => 0,                          /*
        |*/],[ /*- 404 method - - - - - - - - - - -        | <|--- Riemann's critic line
        |*/     "method" => "notFound",                   /*/
        |*/     "router" => ["*", "*"],                   /*/
        |*/     "priority" => -1,                         /*
        |*/  ],[ /* Socket demo - - - - - - - - - -        |/
        |*/     "method" => "socketNotFound",             /*/
        |*/     "router" => ["SOCKET", "*"],              /* <|--- Riemann's zeros
        |*/     "priority" => -1,                         /*/
        |*/ ],];/*=======================================*/


    /**
     * display a homepage
     * @param string $httpResponse
     * @param array $args
     * @return bool
     */
    public function home(string &$httpResponse, $args = []): bool {
        $httpResponse = "HOMMME";
        return true;
    }

    /**
     * return a
     * @param string $httpResponse
     * @param array $args
     * @return bool
     */
    public function notFound(string &$httpResponse, $args = []): bool {
        $httpResponse = "404";
        return true;
    }

    public function socketNotFound(string &$httpResponse, $args = []): bool {
        $httpResponse = "404";
        return true;
    }
}
